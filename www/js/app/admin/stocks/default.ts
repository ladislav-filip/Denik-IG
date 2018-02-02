/// <reference path="../../../../tsd/jquery/index.d.ts" />
/// <reference path="../../../../tsd/bootstrap/index.d.ts" />

class Stock {

    urlDelete: string;

    urlRefresh: string;

    stockId: number;

    $tr: JQuery;

    constructor(urlRefresh: string, urlDelete: string) {

        this.urlRefresh = urlRefresh;
        this.urlDelete = urlDelete;
        $("#table tbody").find("a[data-refresh]").click((e) => this.refreshClick(e));
        $("#table tbody").find("a[data-remove]").click((e) => this.deleteClick(e));
        $("#dlgDelete").find("[data-save]").click((e) => this.deleteSaveClick(e));
    }

    rowClick(e: any): void {
        e.preventDefault();
        this.$tr = $(e.target).closest("tr");
        this.stockId = this.$tr.data("id");
    }

    refreshClick(e: any): void {
        this.rowClick(e);
        var spinner = 'fa fa-spinner fa-spin fa-1x fa-fw';
        var $ico = this.$tr.find('[data-refresh]').find('i');
        $ico.removeAttr('class');
        $ico.addClass(spinner);
        $ico.tooltip('hide');

        $.post(this.urlRefresh, {idx: this.stockId}).done((payload) => {
            this.$tr.find("[data-price]").text(payload.data.price);
            this.$tr.find("[data-updated]").text(payload.data.updated);
            $ico.removeAttr('class');
            $ico.addClass('fa fa-calculator fa-1x');
        });
    }

    deleteClick(e: any): void {
        this.rowClick(e);
        $("#dlgDelete").modal('show');
    }

    deleteSaveClick(e: any): void {
        $("#dlgDelete").modal('hide');
        $.post(this.urlDelete, {idx: this.stockId}).done((payload) => {
            if (payload.error) {
                alert(payload.error);
            }
            else {
                this.$tr.remove();
                this.$tr = null;
            }
        });
    }

}