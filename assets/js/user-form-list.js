(function ($) {
    $(document).ready(function () {

        let id = parseInt($('.add-more').data('id'));
        $("body").on("click", ".add-more", function () {
            id = id +1;
            $(".copy").find('input.form-control').attr('placeholder', "To'lov");
            $(".copy").find('button').data('id', id);
          //  $(".copy").find('.control-group').addClass('after-add-more');
            let html = $(".copy").html();
             $(this).parents(".control-group").after(html);
        });

        $("body").on("click", ".remove", function () {
            $(this).parents(".control-group").remove();
             id = id-1;
        });
    });
})(jQuery);
