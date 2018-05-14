// load merchant currencies
$(document).ready(function () {

    var merchant_id = $("#COINTOPAY_MERCHANT_ID").val();
    getCoin(merchant_id);

    $("#COINTOPAY_MERCHANT_ID").keyup(function () {
        var id = this.value;
        getCoin(id);
    });
});

function getCoin(id) {

    var selected_currency = $('#selected_currency').val();
    var currency_url = '../modules/cointopay/ajaxcall.php';
    if (id.length > 0) {
        $.ajax({
            url: currency_url,
            type: "POST",
            data: {merchant: id},
            success: function (result) {

                var data = $.parseJSON(result);
                var str = "";
                var $crypto_currency = $('#crypto_currency');

                $.each(data, function (index, value) {
                    if (data[index].id != 0) {
                        str += "<option value='" + data[index].id + "'> " + data[index].name + "</option>";
                    }
                });

                $crypto_currency.html(str);
                if (selected_currency != '' && selected_currency != 0) {
                    $crypto_currency.val(selected_currency);
                }
            },
            error: function () {
                console.log("error");
            }
        });
    }
}