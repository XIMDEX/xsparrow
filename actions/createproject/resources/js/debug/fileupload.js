$(function() {
    // the widget definition, where "custom" is the namespace,
    // "colorize" the widget name
    $.widget( "ximdex.simplefileupload", {
   		
   		defaults: {
   			OnChange: null,
        BeforeChange:null,
   			maxSize:300000,
   			fileTypes:["image/jpeg", "image/png", "image/gif"],
   			filename: false
   		},

      filename:false,
   		
   		_create: function(opt){
   			this.options = $.extend(this.defaults, this.options);
   			this._on(this.element, {
   				change: "change"
   			});
        if (this.options.filename)
          this.filename=this.options.filename+this.element.attr("data-tag");
   		},

   		change: function(e){
        this._trigger("BeforeChange");
   			if (this.options.url){
   				// fetch FileList object
  				var files = e.target.files || e.dataTransfer.files;

  				// process all File objects
  				for (var i = 0, f; f = files[i]; i++) {
  					//ParseFile(f);
  					this._uploadFile(f);
  				}	
   			}   			
   		},

   		_uploadFile: function(file){
     		
        var xhr = new XMLHttpRequest();
        var that = this;

        if (!xhr.upload){
          alert("Your browser doesn't support this input");
        }else if ($.inArray(file.type, this.options.fileTypes) === -1){
          alert("Filetype not allowed. Jpg, png or gif, please");
        }else if (file.size > this.options.maxSize){
          alert("File size must be under "+this.options.maxSize);
        }else{
  			
    			// start upload
    			xhr.open("POST", this.options.url, true);
    			xhr.onreadystatechange=function(){
    			  if (xhr.readyState==4 && xhr.status==200)
    			    {
                var data = eval("(" + xhr.response + ")");
    			    	that._trigger("OnChange", that.element, data);
    			    }
    		  	}
    				
    			var name = this.filename? this.filename+this._getExtension(file.name): file.name;	

    			xhr.setRequestHeader("X_FILENAME", name);
    			xhr.send(file);
     		}
      },

      _getExtension: function(filename){
        var result = "";
        var pos = filename.lastIndexOf(".");
        if (pos){
          result = filename.substring(pos);
        }
        return result;
      }
   	});
});