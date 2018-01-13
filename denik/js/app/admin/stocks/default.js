/// <reference path="../../../../tsd/jquery/index.d.ts" />
var Stock = /** @class */ (function () {
    function Stock(url) {
        $("#table tbody").find("a[data-refresh]").click(function (e) {
            e.preventDefault();
            var $tr = $(e.target).closest("tr");
            var id = $tr.data("id");
            var spinner = 'fa fa-spinner fa-spin fa-1x fa-fw';
            var $ico = $tr.find('[data-refresh]').find('i');
            $ico.removeAttr('class');
            $ico.addClass(spinner);
            $.post(url, { idx: id }).done(function (payload) {
                $tr.find("[data-price]").text(payload.data.price);
                $tr.find("[data-updated]").text(payload.data.updated);
                $ico.removeAttr('class');
                $ico.addClass('fa fa-calculator fa-1x');
            });
        });
    }
    return Stock;
}());
