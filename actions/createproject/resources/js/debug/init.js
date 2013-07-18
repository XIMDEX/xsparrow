X.actionLoaded(function (event, fn, params){
  $("input[type='color']").ColorPicker(
  {
    onSubmit: function(hsb, hex, rgb, el) {
                $(el).css("background-color","#"+hex);
                $(this).ColorPicker("hide");
              },

    onChange : function(hsb, hex, rgb){
                var el = $(this).data("colorpicker").el;
                $(el).css("background-color","#"+hex);

              }

  }).bind('keyup', function(){
        $(this).ColorPickerSetColor(this.value);
  });


  fn("input#title").bind("keyup", function(){

    var texto = $(this).val();
    var actionContainer = $(this).parentsUntil(".action_container");
    $("div.bsPreviewTitle h4", actionContainer).text(texto);


  });


 fn("select.ximdexInput").inputSelect();

  fn('.advanced-btn').click(
    function(){
      $(this).next("div").toggleClass("advanced-settings");
  });

  var btn = fn('.submit-button').get(0);
  $(btn).click(function(event, button){
      projectCreation.createProject(fn);
  });

  fn("li.theme div.actions a.select").click(function(){

	return false;
  });


  fn("li.theme div.actions a.custom").click(function(){

	var actionWidth = fn("div.action_container").width()*-1;

	fn("div.action_content form").animate({"margin-left":actionWidth+"px"}, "slow");
  fn("div.action_content div.customize-template-form").animate({"margin-left":"0px"}, "slow");
	return false;
  });

    fn('select#fonts').fontSelector({
    options: {
      inSpeed: 250,
      outSpeed: "slow",
    },
    fontChange: function(e, ui) {
      //alert("The font is set to "+ui.font+" (was "+ui.oldFont+" before)");
    },
    styleChange: function(e, ui) {
      //alert("The value of "+ui.style+" was set to "+ui.value);
    }
  });

});
