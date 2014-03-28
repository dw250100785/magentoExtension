/**
 * BigHippo from unexpected it
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.unexpectedit.com/LICENSE-M1.txt
 *
 * @category   iCloset
 * @package    BigHippo_iCloset
 * @copyright  Copyright (c) 2003-2016 unexpected it Co. (http://www.unexpectedit.com)
 * @license    http://www.unexpectedit.com/LICENSE-M1.txt
 */

(function($) {
	iCloset = {
		appVersion : "2.1.0",
		
		//Personalized by user
		urlSource : '/icloset/webservice', //'http://icloset.ipascual.com:8888/ffvirtual57/webservice/api21',
		urlModule : window.location.protocol + '//' + window.location.host + '/icloset/index', 
		icWrapper : '', //"#ic-wrapper",
		imgLoading : '', //'../images/icloset/loading.gif',
		pageSize : 12, // 12
		
		//HTML Components
		icFilter : null,
		icProducts : null,
		icModel : null,
		
		//Global vars
		zIndex : 0,
		scope : null,
		page : 0,
		categoryId : "",
		mouseX: 0,
		mouseY: 0,
		selection: {},
		categories: {},
		modelImage : null,
		
		//Look selection
		icSelection : Array(),
		//Drag and Drop
		icProductSelected : null,
		
		init: function(scope) {
			//Save main container
			this.scope = scope;

			//Get ready all HTML components
			this.icWrapper = $(this.icWrapper);
			this.icFilter = $($(" #ic-filter", this.icWrapper)[0]);
			this.icProducts = $($(" #ic-products", this.icWrapper)[0]);
			this.icModel = $($(" #ic-model", this.icWrapper)[0]);
			//...
			
			//Create model
			iCloset.initModel();
			//Load categories
			iCloset.initCategories();
			//Load products
			iCloset.loadProducts("", 1);
			
			//Track mouse position
			$(document).mousemove(function(e){
				iCloset.mouseX = e.pageX - iCloset.icWrapper.offset().left;
				iCloset.mouseY = e.pageY - iCloset.icWrapper.offset().top;
			});
			
			//Reload last session
			iCloset.loadSession();
		},

		/*
		 * Init functions
		 */
		initModel: function() {
			//Set loading
			this.icModel.css("background", "url("+ this.imgLoading +") no-repeat 50% 50%")
			$.ajax({
				url: iCloset.urlSource + "/model",
				data: "scope=" + iCloset.scope,
				type: "GET",
				dataType: "json",
				success: function(source){
					iCloset.modelImage = source.model.image;
					iCloset.icModel.css("background", "url("+ iCloset.modelImage +") no-repeat 50% 50%");
				},
				error: function(data){
				}
			});							
		},

		// Init categories		
		initCategories: function() {
			//Set loading
			this.icFilter.css("background", "url("+ this.imgLoading +") no-repeat 50% 10%")
			this.icFilter.html("");
			$.ajax({
				url: iCloset.urlSource + "/categories",
				data: "scope=" + iCloset.scope,
				type: "GET",
				dataType: "json",
				success: function(source){
					data = source;
					
					//Object to convert id to name in labels
					iCloset.categories = new Object();
					
					//Prepare HTML list					
					var htmlResult = "<ul>";
					//Default category
					var cat=new Object();
					cat.category_id = "";
					cat.name = "All";
					var method = "iCloset.loadProducts(\"" + cat.category_id + "\", 1); return false";
					htmlResult = htmlResult + iCloset.renderCategory(cat, method);
					//Load all categories
					for(var i=0; i<data.categories.length; i++) {
						method = "iCloset.loadProducts(\"" + data.categories[i].category_id + "\", 1); return false";
						htmlResult = htmlResult + iCloset.renderCategory(data.categories[i], method);	
						iCloset.categories[data.categories[i].category_id] = data.categories[i].name;
					}
					htmlResult = htmlResult + "</ul>";
					iCloset.icFilter.html(htmlResult);
					iCloset.icFilter.css("background", "none");
					
					//Refresh
					iCloset.refreshLabels();
				},
				error: function(data){
				}
			});							
		},

		productOrder : function(obj1, obj2) {
			var pos1 = eval(obj1.global_position);
			var pos2 = eval(obj2.global_position);
			
			if(pos1 < pos2) {
				return false;
			}
			return true;
		},
		
		loadProducts: function(categoryId, page) {
			this.categoryId = categoryId;
			this.page = page;
			
			//Set loading
			iCloset.icProducts.html("");
			this.icProducts.css("background", "url("+ this.imgLoading +") no-repeat 50% 50%")
			$.ajax({
				url: iCloset.urlSource + "/products",
				data: "scope=" + iCloset.scope + "&categoryId=" + iCloset.categoryId + "&page=" + iCloset.page + "&pageSize=" + iCloset.pageSize,
				type: "GET",
				dataType: "json",
				success: function(data){
					
					var products = data.products;
					
					//Create html
					var htmlResult = "";
					var totalResult = products.length;
					for(var i=0; i < products.length; i++) {
						htmlResult = htmlResult +  "<div class='ic-product'>";
						var positionType = "drag-drop";

						//Image to grid
						var htmlImage = "<img \
											class='ic-product-img-small' \
										 	src='"+products[i].image_small+"' \
										 	ic-sku='"+products[i].sku+"' \
										 	ic-position='grid' \
										 	ic-image-transparent='"+products[i].image_play+"' \
										 	ic-image-small='"+products[i].image_small+"' \
										 	ic-position-type='"+positionType+"' \
										 	ic-price1='"+products[i].price+"' \
										 	/>";
						htmlResult = htmlResult + htmlImage;
						
						//Description
						htmlResult = htmlResult + iCloset.renderProduct(products[i]);
						htmlResult = htmlResult +  "</div>";
						var imageToDownload = new Image();
						imageToDownload.src = products[i].image_play;
					}
					
					//No products
					if(products.length == 0) {
						htmlResult = "No products found.";	
					}
					
					iCloset.icProducts.html(htmlResult);
					
					//Create drag and drop products
					$.each($(" .ic-product-img-small", iCloset.icWrapper), function(index, value) {
						iCloset.draggable(this);						
					});
					
					//Refresh controllers
					iCloset.loadControllers(data.total, totalResult);
					
					//Backgroud remove
					iCloset.icProducts.css("background", "none");
					
				},
				error: function(data){
				}
			});							
		},
		
		loadControllers: function(totalProducts, totalResult) {
			var backwardMethod = null;
			var forwardMethod = null;
			
			//Backward button
			if(iCloset.page > 1) {
				backwardMethod = "iCloset.loadProducts(iCloset.categoryId, iCloset.page - 1); return false;";
			}
			//Forward button
			if((iCloset.page * iCloset.pageSize) < totalProducts) {
				forwardMethod = "iCloset.loadProducts(iCloset.categoryId, iCloset.page + 1); return false;";
			}
			//Total			
			var totalPages = (totalProducts / iCloset.pageSize);
			if(parseInt(totalPages) < totalPages) {
				totalPages++;	
			}
			totalPages = parseInt(totalPages);
			
			iCloset.refreshLabels();
			iCloset.renderControllers(backwardMethod, forwardMethod, iCloset.page, totalPages);
		},
		
		/*
		 * Drag and Drop system
		 * 
		 */ 
		draggable : function(e) {
			$(e).draggable({ start: function() { iCloset.start(this); }, drag: function() { iCloset.drag(this) }, stop: function() { iCloset.stop(this) } });
			$(e).click(function() { iCloset.bringToFront(this); iCloset.saveSession(); });
		},
		
		bringToFront : function(e) {
			iCloset.zIndex = iCloset.zIndex + 1;			
			$(e).css("z-index", iCloset.zIndex);			
		},
		
		start : function(e) {

			//Product starting from grid
			if($(e).attr("ic-position") == "grid") {
				//Replace product
				var sourceProduct = $(e).clone();
				sourceProduct.css("top", "0");
				sourceProduct.css("left", "0");
				sourceProduct.css("ic-position", "grid");
				sourceProduct.prependTo($(e).parent());
				iCloset.draggable(sourceProduct);

				//Prepare drag&drop product
				$(e).attr("src", $(e).attr("ic-image-transparent"));

				//Add to main wrapper				
				$(e).css("position", "absolute");
				
				/*
				 * Mouse position
				 * 
				 * x = mouse_position + (mouse_posision - grid image left position) + (grid image width / 2)
				 * y = mouse_position + (mouse_posision - grid image top position) + (grid image height / 2)
				 */
				$(e).data('draggable').offset.click.left = -iCloset.mouseX + (iCloset.mouseX - sourceProduct.position().left) + (sourceProduct.width() / 2);
				$(e).data('draggable').offset.click.top = -iCloset.mouseY + (iCloset.mouseY - sourceProduct.position().top) + (sourceProduct.height() / 2);
				

			}
			
			//All products
			iCloset.bringToFront($(e));
		},
		
		drag : function(e) {
		},
		
		stop : function(e) {

			var newProductToSelection = false;
			var deleteProductToSelection = true;
			
			//1) Add to model container. Fix coordinates
			if($(e).attr("ic-position") == "grid") {
				
				//Add to model			
				$(e).appendTo($("#ic-model"));
				
				//Fix position inside the model
				$(e).css("left", $(e).position().left - $("#ic-model").position().left);
				$(e).css("top", $(e).position().top - $("#ic-model").position().top);
			}				
			
			//2) Add/Remove from model
			if(this.isInside(this.icModel, $(e))) {
				//Do not delete product, it was dropped in the model area
				deleteProductToSelection = false;
				
				//Is not the product already in selection
				if(! this.isInSelection($(e)) ) {
					//New product
					newProductToSelection = true;
				}
				else {
					//Product dragged from grid
					if(($(e).attr("ic-position") == "grid")) {
						iCloset.removeFromView(e);
					}
				}
			}
			
			//Add to selection
			if(newProductToSelection) {				
				iCloset.addToSelection(e);
				
				//Mark as product in model
				$(e).attr("ic-position", "model");
			}
			//Remove from selection
			if(deleteProductToSelection){
				iCloset.removeFromView($(e));
				iCloset.removeFromSelection(e);
			}
			
			//Save all selection
			iCloset.saveSession();
		},
		
		isInside : function(obj1, obj2) {
			var inside = true;
			var obj2CenterX = obj2.position().left + (obj2.width() / 2);
			var obj2CenterY = obj2.position().top + (obj2.height() / 2);

			// x limits			
			if(obj2CenterX < 0) {
				inside = false;	
			}
			if(obj2CenterX > obj1.width()) {
				inside = false;	
			}
			// y limits			
			if(obj2CenterY < 0) {
				inside = false;	
			}
			if(obj2CenterY > obj1.height()) {
				inside = false;	
			}

			return inside;
		},
		
		removeFromView : function (e) {
			//Remove from view
			$(e).remove();
		},
		
		/*
		 * Selection
		 * 
		 */
		addToSelection : function (e) {
			iCloset.selection[$(e).attr("ic-sku")] = $(e);
			
			//Refresh totals
			iCloset.refreshLabels();
		},
		
		isInSelection : function (e) {
			var inSelection = false;
			
			$.each(iCloset.selection, function(index, value) {
				if(index == $(e).attr("ic-sku")) {
					inSelection = true;
				}
			});
			
			return inSelection;
		},

		removeFromSelection : function (e) {
			//Remove from selection
			delete iCloset.selection[$(e).attr("ic-sku")];
			
			//Refresh totals
			iCloset.refreshLabels();
		},
		
		formatNumber : function(nStr)
		{
			nStr = eval(nStr);
			nStr = nStr.toPrecision(); //remove decimals
			var separateSymbol = '.';
			
			nStr += '';
			x = nStr.split(separateSymbol);
			x1 = x[0];
			x2 = x.length > 1 ? separateSymbol + x[1] : '';
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(x1)) {
				x1 = x1.replace(rgx, '$1' + separateSymbol + '$2');
			}
			return x1 + x2;
		},
				
		refreshLabels : function (e) {
			//Filter label
			var filterLabel = "";
			if(iCloset.categoryId != "") {
				filterLabel = iCloset.categories[iCloset.categoryId];	
			}
			
			//Checkout labels
			var totalProducts = 0;
			var total1 = 0;
			$.each(iCloset.selection, function(index, value) {
				total1 = total1 + eval(this.attr("ic-price1"));
				totalProducts++; 
			});

			//Render			
			iCloset.renderCategoryLabel(iCloset.categoryId); 
			iCloset.renderTotalLabel(totalProducts, total1);
		},
		
		getSelectionSKUs : function() {
			//Create SKU string			
			var refs = new Array();
			var i = 0;
			$.each(iCloset.selection, function(index, value) {
				refs[i] = index;
				i++;
			});
			var stringRefs = refs.toString();
			
			return stringRefs;
		},

		saveSession : function() {
			//Selection images
			var skus = "";
			var selectionHtml = "";
			$.each($("#ic-model [ic-position|='model']"), function (index, value) {
				value = $('<div>').append($(this).clone()).html();
				selectionHtml = selectionHtml + value;
				skus = $(this).attr('ic-sku') + "," + skus;
			});
			selectionHtml = encodeURI(selectionHtml);

			//Sesssion params
			$.ajax({
			  type: 'POST',
			  url: iCloset.urlModule + "/save",
			  cache : false,
			  data: { 
			  		"selection" : selectionHtml,
			  		"skus" : skus,
			  		"zIndex" : iCloset.zIndex,
			  		"scope" : iCloset.scope 
			  		}
			});
		},
		
		loadSession : function() {
			$.ajax({
				// http://icloset.ipascual.com:8888/ffvirtual57/icloset/index/load
				url: iCloset.urlModule + "/load",
				data: null,
				type: "GET",
				dataType: "json",
				cache : false,
				success: function(source){
					if(source.scope == iCloset.scope) {
						//Selection images
						var selectionImages = decodeURI(source.selection);
						var product = $(selectionImages).appendTo($("#ic-model"));
	
						//Make them draggable
						$.each($("#ic-model [ic-position|='model']"), function (index, value) {
							iCloset.draggable(this);
							iCloset.addToSelection(this);
						});
						
						//zIndex
						iCloset.zIndex = eval(source.zIndex);					
	
						//Scope
						iCloset.scope = source.scope;
					}					
					
				},
				error: function(data) {
					
				}
			});
		},
		
		/*
		 * Events
		 */
		renderCategory : function(category, method) {
			var html = "<li id='ic-cat-"+category.category_id+"'>";
			html += "<a href='#' onclick='"+method+"' >";
			html += category.name + "</a>";
			html += "</li>"; 
			return html;
		},
		
		renderProduct : function(product) {
			return product.name + "<br />" + iCloset.currency + iCloset.formatNumber(product.price);			
		},
		
		renderTotalLabel : function(totalProducts, totalPrice) {
			$("#ic-total").html("");
			if(totalProducts > 0) {
				$("#ic-total").html(totalProducts + " products " + iCloset.currency + totalPrice);
				$("#ic-total").html($("#ic-total").html() + '&nbsp;<button type="button" title="Buy Now" class="button" onclick="window.location=\''+ iCloset.urlSource + "/buy" +'\';"><span><span>Buy Now</span></span></button>');
			}
		},
		
		renderCategoryLabel : function(categoryId) {
			//Category name label
			$("#ic-filter-label").html(iCloset.categories[categoryId]);

			//Category buttons
			$("#ic-cat-").removeClass("selected");
			$.each(iCloset.categories, function(index, value) {
				$("#ic-cat-" + index).removeClass("selected");	
			});
			$("#ic-cat-" + categoryId).addClass("selected");
		},
		
		renderControllers : function(backwardMethod, forwardMethod,currentPage,totalPages) {
			var htmlControllers = "";

			if(backwardMethod != null) {
				htmlControllers = htmlControllers + "<a href='#' onclick='" + backwardMethod + "' class='ic-backward'>&lt; &lt; back</a>";
			}
			if(forwardMethod != null) {
				htmlControllers = htmlControllers + "<a href='#' onclick='" + forwardMethod + "' class='ic-forward'>next &gt; &gt;</a>";
			}
			$("#ic-controllers").html(htmlControllers);
			
			var htmlPages = "";
			if(totalPages > 1) {
				htmlPages = htmlPages + "" + iCloset.page + " / " + totalPages + "";
			}
			$("#ic-pages").html(htmlPages);
		}
	}

})(jQuery);