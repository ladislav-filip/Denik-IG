/// <reference path="../../../../tsd/jquery/index.d.ts" />
/// <reference path="../../../../tsd/bootstrap/index.d.ts" />
var Stock = /** @class */ (function () {
    function Stock(urlRefresh, urlDelete) {
        var _this = this;
        this.urlRefresh = urlRefresh;
        this.urlDelete = urlDelete;
        $("#table tbody").find("a[data-refresh]").click(function (e) { return _this.refreshClick(e); });
        $("#table tbody").find("a[data-remove]").click(function (e) { return _this.deleteClick(e); });
        $("#dlgDelete").find("[data-save]").click(function (e) { return _this.deleteSaveClick(e); });
    }
    Stock.prototype.rowClick = function (e) {
        e.preventDefault();
        this.$tr = $(e.target).closest("tr");
        this.stockId = this.$tr.data("id");
    };
    Stock.prototype.refreshClick = function (e) {
        var _this = this;
        this.rowClick(e);
        var spinner = 'fa fa-spinner fa-spin fa-1x fa-fw';
        var $ico = this.$tr.find('[data-refresh]').find('i');
        $ico.removeAttr('class');
        $ico.addClass(spinner);
        $ico.tooltip('hide');
        $.post(this.urlRefresh, { idx: this.stockId }).done(function (payload) {
            _this.$tr.find("[data-price]").text(payload.data.price);
            _this.$tr.find("[data-updated]").text(payload.data.updated);
            $ico.removeAttr('class');
            $ico.addClass('fa fa-calculator fa-1x');
        });
    };
    Stock.prototype.deleteClick = function (e) {
        this.rowClick(e);
        $("#dlgDelete").modal('show');
    };
    Stock.prototype.deleteSaveClick = function (e) {
        var _this = this;
        $("#dlgDelete").modal('hide');
        $.post(this.urlDelete, { idx: this.stockId }).done(function (payload) {
            if (payload.error) {
                alert(payload.error);
            }
            else {
                _this.$tr.remove();
                _this.$tr = null;
            }
        });
    };
    return Stock;
}());
