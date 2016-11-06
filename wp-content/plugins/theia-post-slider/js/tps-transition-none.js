/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */
var tps = tps || {};
tps.transitions = tps.transitions || {};
tps.transitions.none = function (me, previousIndex, index) {
    var $ = jQuery;

    // Remove previous slide
    var previousSlide = previousIndex !== null ? $(me.slides[previousIndex].content) : null;
    if (previousSlide) {
        previousSlide.detach();
    }

    // Set the current slide
    var slide = $(me.slides[index].content);
    me.slideContainer.append(slide);

    if (previousSlide) {
        // Call event handlers
        me.onNewSlide();
    }

    return [];
};