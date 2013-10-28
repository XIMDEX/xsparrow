X.actionLoaded(function (event, fn, params){

	//Define X.Sparrow object and its property stylesMap
	//stylesMap maps the attribute from the xml to native Javascript style
    X.Sparrow = {
					stylesMap:{
						"font-color":"color",
						"background-color": "backgroundColor",
						"background-image": "backgroundImage",
						"background-repeat": "backgroundRepeat",
						"background-position": "backgroundPosition",
						"align": "textAlign",
						"font-size": "fontSize",
						"font-family": "fontFamily"
					}
				};

		
	//Object for customize and create a ximdex project
    X.SparrowTheme = Object.xo_create({

		xml: "",
		$iframe: false, //iframe object. Just to make faster 
		$currentHtmlTag: false, //iframe html tag associated to the focus input
		$currentXmlTag: false, //Xml tag associated to focus input

		/**
		* Init function for X.SparrowTheme object		
		*/
		_init:function(){
	        var that = this;
			//set the iframe property
	        fn("iframe").load(function(){

				that.$iframe = $(this).contents().find("body");
				that.$iframe.find(".xsparrow-header-title").attr("contentEditable", true).on("keyup",
					function(){
						//Set title text
  						var titleText = $(this).text();
    					$(that.xml).find("header-title").text(titleText);
  	   					fn("input[name='xml']").val(that.xml.documentElement.outerHTML) ;
					});
				that.$iframe.find(".xsparrow-header-subtitle").attr("contentEditable", true).on("keyup",
					function(){
						var subtitleText = $(this).text();
						$(that.xml).find("header-subtitle").text(subtitleText);
						fn("input[name='xml']").val(that.xml.documentElement.outerHTML) ;
					});

				fn("select.font-selector").fontSelector({
					fontChange: function(widget, fonts){
						var tag = this.getAttribute("data-tag");
						var attribute = this.getAttribute("data-attribute");
						if (tag=="body"){
							var currentHtmlTag = theme.$iframe[0];	
						}else{
							var currentHtmlTag = theme.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];
						}
					
						var currentXmlTag = theme.xml.getElementsByTagName(tag)[0];
						theme.setCurrentHtmlTag(currentHtmlTag);
						theme.setCurrentXmlTag(currentXmlTag);
						theme.setXml(tag, attribute, fonts.font);
					}
				});
			});

			var backgroundPositionLabels = {
				lt: "left top",
				rt: "right top",
				rb: "right bottom",
				lb: "left bottom",
				cc: "center center"
			}

	        //Click on every custom link.There is a custom link per project
		      fn("li.theme div.actions a.custom").click(this._selectTheme.bind(this));

			//InputSelect creation. Redefined onchange and onhover events.
	        fn("select.ximdexInput").inputSelect({
				//OnChange update html and xml related tag
				onChange: function(){
					var tag = this.getAttribute("data-tag");
					var attribute = this.getAttribute("data-attribute");
					var currentHtmlTag = false;
					if (tag == "body"){
						currentHtmlTag = that.$iframe[0];
					}else{
						currentHtmlTag = that.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];	
					}
			        
					var currentXmlTag = that.xml.getElementsByTagName(tag)[0];
					that.setCurrentHtmlTag(currentHtmlTag);
					that.setCurrentXmlTag(currentXmlTag);
					var value =  $(this).find(":selected").val();
					if (attribute == "background-position"){
						value = backgroundPositionLabels[value];
					}
					that.setXml(tag,attribute,value);
				},
				//Onhover update only html related tag
				onHover: function(event){
					
				}
			});

			fn('.custom_options h3').click(this._toggleOptions.bind(this));
		},

		/**
		*Set current html selected tag
		*/
      	setCurrentHtmlTag : function(tag){
			if (tag){
				this.$currentHtmlTag = $(tag);
			}else{
				this.$currentHtmlTag = false;
			}
		},

		/**
		*Set current xml selected tag
		*/			
		setCurrentXmlTag: function(tag){
			if (tag){
				this.$currentXmlTag = $(tag);
			}else{
				this.$currentXmlTag = false;
			}
		},

		/**
		*Update xml and html document.
		*This function can received an undefined number of paremeters.
		*2 parameters means tagName and value.
		*3 parameters means tagName, attributeName and value.
		*/
      	setXml: function(){
			
	    	switch (arguments.length){
	      		case 2: //Tag value. There isnt attributeName
		            this.$iframe.find("."+arguments[0]).text(arguments[1]);
		            this.xml.find(arguments[0]).text(arguments[1]);
					break;
          		case 3: //Attribute value. And its a css property
					//Converting the ximdex attribute name to javascript style property name.
					var jsStyle=X.Sparrow.stylesMap[arguments[1]];					
					if (!jsStyle){
						jsStyle = arguments[1];						
					}
					//Setting style and xml attribute value.						
		            this.$currentHtmlTag[0].style[jsStyle] = arguments[2];
		            this.$currentXmlTag[0].setAttribute(arguments[1],arguments[2]);
					break;
        	}

        	fn("input[name='xml']").val(this.xml.documentElement.outerHTML) ;
      	},
      
		/**
		* Function on click on customize theme link.
		*/		
		_selectTheme: function(event){

			var themeName = $(event.currentTarget).attr("data-theme");
			var src = X.restUrl+"?action=createproject&mod=XSparrow&method=loadPreview";
			src += "&theme="+themeName;
			$("iframe").attr("src",src);			

			var actionWidth = fn("div.action_container").width()*-1;
			fn("input[name='theme']").val(themeName);
				//fn("form").animate({"margin-left":actionWidth+"px"}, "slow");
				//fn("div.action_content div.customize-template-form").animate({"margin-left":"0px"}, "slow");
				this.loadXml(themeName);
				
				return false;
	      	},

		/**
		* Slide the Header, body or footer forms at customization.
		*/
	    _toggleOptions: function(event){
	        $(this).next().slideToggle();
	        $(this).toggleClass('opened').toggleClass('closed');
     	},

		/**
		*	Get Xml configuration for the selected theme.
		*/
      	loadXml:function(theme){
	        var url = X.baseUrl+"/?mod=XSparrow&action=createproject&method=getTheme";
	        var that = this;
	        $.ajax({
	        	url:url,
          		data:{theme:theme},
				dataType: "xml",
				success: function(data){
					that.xml = data;
					fn("input[name='xml']").val(data.documentElement.outerHTML);
					fn("div.custom-colorpicker").each(function(){
						var tag = $(this).attr("data-tag");
						var attribute = $(this).attr("data-attribute");
						var color = $(that.xml).find(tag).attr(attribute);
						if (color && color != "")
							if (color.indexOf("#")!== -1){
								color=color.substring(1);								
							}
							$(this).css("background-color","#"+color);
							$(this).ColorPickerSetColor(color);
					});

	          	},
	          	error: function(data){

	          	}
	        });
      	}
    });

	//Creating SparrowTheme object
	var theme = new X.SparrowTheme();

	fn("input[type='file']").simplefileupload({
			url: X.baseUrl+"/?mod=XSparrow&action=createproject&method=uploadImage",
			filename:"background-",
			BeforeChange: function(){
				var tag = this.getAttribute("data-tag");
				if (tag=="body"){
					var currentHtmlTag = theme.$iframe[0];	
				}else{
					var currentHtmlTag = theme.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];
				}
				
				var currentXmlTag = theme.xml.getElementsByTagName(tag)[0];
				theme.setCurrentHtmlTag(currentHtmlTag);
				theme.setCurrentXmlTag(currentXmlTag);
			},
			OnChange: function(element, data){
				theme.setXml($(element.target).attr("data-tag"),$(element.target).attr("data-attribute"),"url("+data.url+")");
				var dataTag=$(element.target).attr("data-tag");
				var dataAttribute=$(element.target).attr("data-attribute");
				theme.xml.getElementsByTagName(dataTag)[0].setAttribute(dataAttribute,data.resource);
				fn("input[name='xml']").val(theme.xml.documentElement.outerHTML) ;
				$(element.target).parents("dd").next(".background-selectors").removeClass("hidden");
			}
		});

	fn(".background-selectors .remove-image").on("click",function(){
		var tag = $(this).attr("data-tag");
		if (tag=="body"){
			var currentHtmlTag = theme.$iframe[0];	
		}else{
			var currentHtmlTag = theme.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];
		}
		var currentXmlTag = theme.xml.getElementsByTagName(tag)[0];
		theme.setXml(tag,"background-image","");
		theme.setXml(tag,"background-repeat","");
		theme.setXml(tag,"background-position","");		
		$(this).parents(".background-selectors").addClass("hidden");
		return false;

	});

	//Creating ColorPicker objects
	fn("input.input_colorpicker").ColorPicker({

		onShow: function(){
				var tag = this.getAttribute("data-tag");
				if (tag=="body"){
					var currentHtmlTag = theme.$iframe[0];	
				}else{
					var currentHtmlTag = theme.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];
				}
				
				var currentXmlTag = theme.xml.getElementsByTagName(tag)[0];
				theme.setCurrentHtmlTag(currentHtmlTag);
				theme.setCurrentXmlTag(currentXmlTag);
		},
		onSubmit: function(hsb, hex, rgb, el) {
		    $(el).css("background-color","#"+hex);
		    $(this).ColorPicker("hide");
			theme.setCurrentHtmlTag(false);
			theme.setCurrentXmlTag(false);
		},

		onChange : function(hsb, hex, rgb){
		    var el = $(this).data("colorpicker").el;
		    $(el).css("background-color","#"+hex);
		    //theme.setXml(tag,attribute,value);
		    theme.setXml($(el).attr("data-tag"),$(el).attr("data-attribute"),"#"+hex);
		}

	}).bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});


	$("input[type='range']").each(function(){

        $element = $(this);

        var $newElement = $("<div/>").addClass($element.attr("class"));
        $newElement.attr("name",$element.attr("name"));
        $newElement.attr("id",$element.attr("id"));
        $newElement.attr("min",$element.attr("min"));
        $newElement.attr("max",$element.attr("max"));
        $newElement.attr("step",$element.attr("step"));
        $newElement.attr("data-tag",$element.attr("data-tag"));
        $newElement.attr("data-attribute",$element.attr("data-attribute"));

        $element.after($newElement);
        $element.remove();

        var min = $newElement.attr("min")? parseInt($newElement.attr("min")):1,
                max = $newElement.attr("max")? parseInt($newElement.attr("max")): 100,        
                step = $newElement.attr("step")? parseInt($newElement.attr("step")): 1;
        var opt = {
                range:"min",
                min:min,
                max:max,
                step:step,
                change: function(event,ui){
                		
                        $("a.ui-slider-handle", $(event.target)).attr("title",ui.value);
                        theme.setXml(event.target.getAttribute("data-tag"),
                        	event.target.getAttribute("data-attribute"),ui.value+"%");
                        
                },
                start: function(event, ui){
                	var tag = event.currentTarget.getAttribute("data-tag");
					if (tag=="body"){
						var currentHtmlTag = theme.$iframe[0];	
					}else{
						var currentHtmlTag = theme.$iframe[0].getElementsByClassName("xsparrow-"+tag)[0];
					}
				
					var currentXmlTag = theme.xml.getElementsByTagName(tag)[0];
					theme.setCurrentHtmlTag(currentHtmlTag);
					theme.setCurrentXmlTag(currentXmlTag);
                }
        }
        $newElement.slider(opt);
}

);

	

	fn("input#title").bind("keyup", function(){
		var texto = $(this).val();
	    var actionContainer = $(this).parentsUntil(".action_container");
	    $("div.bsPreviewTitle h4", actionContainer).text(texto);
  	});

	fn('.advanced-btn').click(
    	function(){
			$(this).next("div").toggleClass("advanced-settings");
  		});

  	
  	fn("li.theme div.actions a.select").click(function(){

		return false;
  	});

  	fn("li.theme div.actions a.custom").click(function(){

  		fn(".theme-selection").animate({"margin-left":"-120%"},1500);
  		fn(".customize-template-form").animate({"margin-left":"0%"},1500);
		return false;
  	});


	$('.custom_options h3').click(function(){
			
		$(this).next().slideToggle(function(){
			$(this).css("overflow","visible");
		});
		$(this).toggleClass('opened').toggleClass('closed');
		
		});
	});