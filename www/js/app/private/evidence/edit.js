/// <reference path="../../../../tsd/jquery/index.d.ts" />
/// <reference path="../../../../tsd/bootstrap/index.d.ts" />
var EditEvidence = /** @class */ (function () {
    function EditEvidence(urlSuggestStock) {
        this.urlSuggestStock = urlSuggestStock;
        var $frm = $("#frm-recordEdit");
        var $el = $frm.find("[name=code]");
        var $stock_id = $frm.find("[name=stock_id]");
        var $stock_name = $frm.find("[name=stock_name]");
        var cache = {};
        $el.autocomplete({
            minLength: 2,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response(cache[term]);
                    return;
                }
                $.getJSON(urlSuggestStock, request, function (data, status, xhr) {
                    cache[term] = data;
                    response(data);
                });
            },
            select: function (event, ui) {
                $stock_id.val(ui.item.id);
                $stock_name.prop("disabled", true).val(ui.item.name);
            },
            search: function (event, ui) {
                $stock_id.val("");
                $stock_name.prop("disabled", false);
            }
        });
    }
    return EditEvidence;
}());
