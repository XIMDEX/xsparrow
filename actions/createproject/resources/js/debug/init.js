X.actionLoaded(function (event, fn, params){

	//Define X.Sparrow object and its property stylesMap
	//stylesMap maps the attribute from the xml to native Javascript style
    X.Sparrow = {
					stylesMap:{
						"font-color":"color",
						"background-color": "backgroundColor",
						"align": "textAlign"
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
			});

			//Click on every custom link.There is a custom link per project
		      fn("li.theme div.actions a.custom").click(this._selectTheme.bind(this));

			//InputSelect creation. Redefined onchange and onhover events.
	        fn("select.ximdexInput").inputSelect({
				//OnChange update html and xml related tag
				onChange: function(){
					var tag = this.getAttribute("data-tag");
					var attribute = this.getAttribute("data-attribute");
			        var currentHtmlTag = that.$iframe[0].getElementsByClassName(tag)[0];
					var currentXmlTag = that.xml.getElementsByTagName(tag)[0];
					that.setCurrentHtmlTag(currentHtmlTag);
					that.setCurrentXmlTag(currentXmlTag);
					var value =  $(this).find(":selected").val();
					that.setXml(tag,attribute,value);
				},
				//Onhover update only html related tag
				onHover: function(event){
					console.log("aaa");
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
					fn("input[name='xml']").val(data.documentElement.outerHTML) ;
	          	},
	          	error: function(data){

	          	}
	        });
      	}
    });

	//Creating SparrowTheme object
	var theme = new X.SparrowTheme();

	//Creating ColorPicker objects
	fn("input.input_colorpicker").ColorPicker({

		onShow: function(){
				var tag = this.getAttribute("data-tag");
				var currentHtmlTag = theme.$iframe[0].getElementsByClassName(tag)[0];
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

	fn("select.font-selector").fontSelector();

	fn("input#title").bind("keyup", function(){
		var texto = $(this).val();
	    var actionContainer = $(this).parentsUntil(".action_container");
	    $("div.bsPreviewTitle h4", actionContainer).text(texto);
  	});

	fn('.advanced-btn').click(
    	function(){
			$(this).next("div").toggleClass("advanced-settings");
  		});

  	var btn = fn('.submit-button').get(0);
  	$(btn).click(function(event, button){
  	        
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