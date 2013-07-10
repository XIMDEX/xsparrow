(function($) {

        $.extend($.ui.slider.prototype, {

                _init: function(){

                        $element = this.element;
                	var min = $element.attr("min")? parseInt($element.attr("min")):1,
                	max = $element.attr("max")? parseInt($element.attr("max")): 100,
                	step = $element.attr("step")? parseInt($element.attr("step")): 1;
                	this.options.min = min;
                	this.options.max = max;
                	this.options.step = step;
                        $("a.ui-slider-handle", this.element).attr("title",min);
                        this.options.slide = function(event,ui){
                                $("a.ui-slider-handle", this.element).attr("title",ui.value);
                        };
                }

        });

})(jQuery);

var createSlider = function(){
        $element = $(this);
        var $newElement = $("<div/>").addClass($element.attr("class"));
        $newElement.attr("name",$element.attr("name"));
        $newElement.attr("id",$element.attr("id"));
        $newElement.attr("min",$element.attr("min"));
        $newElement.attr("max",$element.attr("max"));
        $newElement.attr("step",$element.attr("step"));
        $element.after($newElement);
        $element.remove();
        $newElement.slider({});
}
$(function() {
	// Stuff to do as soon as the DOM is ready;
	$("input[type='range']").each(createSlider);
});


