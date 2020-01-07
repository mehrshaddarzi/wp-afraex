(function (window, undefined) {
    'use strict';

    /*
    NOTE:
    ------
    PLACE HERE YOUR OWN JAVASCRIPT CODE IF NEEDED
    WE WILL RELEASE FUTURE UPDATES SO IN ORDER TO NOT OVERWRITE YOUR JAVASCRIPT CODE PLEASE CONSIDER WRITING YOUR SCRIPT HERE.  */

})(window);


jQuery(document).ready(function ($) {

    // Click Btn loading Effect
    $(document).on("submit", "form[data-show-submit-spinner]", function (e) {
        //e.preventDefault();
        $(this).find("button[type=submit]").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">بارگذاری...</span>');
        return true;
    });

    // Only Numeric
    jQuery.fn.ForceNumericOnly =
        function () {
            return this.each(function () {
                $(this).keydown(function (e) {
                    var key = e.charCode || e.keyCode || 0;
                    return (
                        key == 8 ||
                        key == 9 ||
                        key == 13 ||
                        key == 46 ||
                        key == 110 ||
                        key == 190 ||
                        (key >= 35 && key <= 40) ||
                        (key >= 48 && key <= 57) ||
                        (key >= 96 && key <= 105));
                });
            });
        };
    $("input[data-only-numeric]").ForceNumericOnly();

    $('input[data-bank-number]').keydown(function (e) {
        var key = e.charCode || e.keyCode || 0;
        $text = $(this);
        if (key !== 8 && key !== 9) {
            if ($text.val().length === 4) {
                $text.val($text.val() + '-');
            }
            if ($text.val().length === 9) {
                $text.val($text.val() + '-');
            }
            if ($text.val().length === 14) {
                $text.val($text.val() + '-');
            }
        }
    });

    // Chat PM
    $('#attachment_file').on("change", function () {
        var file_name = $(this).val();
        if (file_name !== '') {
            $("form#send_chat_attachment").submit();
        }
    });
    $('#send_pm').on("click", function () {
        $("#submit_btn_pm").click();
    });
    $('i#add_new_attachment').on("click", function () {
        $("input#attachment_file").click();
    });
    if (typeof admin_ajax_refresh_chat_box !== 'undefined') {
        chat_again_check();

        function chat_again_check() {
            jQuery.ajax({
                url: admin_ajax_refresh_chat_box,
                type: 'GET',
                cache: false,
                data: {},
                dataType: "json",
                success: function (data) {
                    if (data.last_id > last_ID) {
                        window.location.href = redirect_link;
                    } else {
                        chat_again_check();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                }
            });
        }
    }


    // New Order
    if ($("form#new_order").length) {

        // Show First Step
        $("div[data-step]").hide();
        $("div[data-step=1]").show();

        // Set Image For Currency Select
        $("#select2-currency, #select2-currency-to").select2({
            templateResult: formatState,
            templateSelection: formatState,
            minimumResultsForSearch: Infinity
        });

        function formatState(opt) {
            if (!opt.id) {
                return opt.text;
            }
            var optimage = $(opt.element).data('image');
            if (!optimage) {
                return opt.text;
            } else {
                var $opt = $(
                    '<span><img src="' + optimage + '" width="25px" style="margin-left: 10px;" /> ' + opt.text + '</span>'
                );
                return $opt;
            }
        }

        // Show Currency Name and Persian
        function show_currency_name() {
            var from = $("select#select2-currency");
            var to = $("select#select2-currency-to");
            $("[data-from-name]").html(from.find(':selected').attr('data-name'));
            $("[data-from-persian]").html(from.find(':selected').attr('data-persian'));
            $("[data-to-name]").html(to.find(':selected').attr('data-name'));
            $("[data-to-persian]").html(to.find(':selected').attr('data-persian'));
        }

        function show_first_step_loading() {
            $("#first_step_loading").show();
            $("#start_change_price").hide();
        }

        function end_of_first_loading() {
            $("#first_step_loading").hide();
            $("#start_change_price").show();
        }

        function request_get_price_of_currency() {

            // Reset All
            show_first_step_loading();
            $("#result_step_1_text").html("");
            $("input[name=to_currency_price]").val(0);
            $("#start_change_price").prop("disabled", true);

            // Start Ajax
            jQuery.ajax({
                url: admin_ajax_refresh_,
                type: 'GET',
                cache: false,
                data: {
                    action: 'convert_currency_price',
                    from_currency: $("select#select2-currency").val(),
                    to_currency: $("select#select2-currency-to").val(),
                    from_currency_price: $("input[name=from_currency_price]").val(),
                },
                dataType: "json",
                success: function (data) {

                    // If Error
                    if (data['error'] == "yes") {
                        alert(data['content']);
                    } else {
                        var t = 'با پرداخت ';
                        t += $("input[name=from_currency_price]").val();
                        t += ' ';
                        t += $("select#select2-currency").find(':selected').attr('data-persian');
                        t += ' ';
                        t += 'مقدار';
                        t += ' ';
                        t += data['price'];
                        t += ' ';
                        t += $("select#select2-currency-to").find(':selected').attr('data-persian');
                        t += ' ';
                        t += 'دریافت میکنید';
                        $("#result_step_1_text").html(t);
                        $("input[name=to_currency_price]").val(data['price']);
                        $("#start_change_price").prop("disabled", false);
                    }

                    end_of_first_loading();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert(data['content']);
                    end_of_first_loading();
                }
            });
        }

        // First Run
        show_currency_name();
        $("input[name=from_currency_price]").val(1);
        request_get_price_of_currency();

        // Change User currency
        $(document).on("change", "#select2-currency, #select2-currency-to", function (e) {
            e.preventDefault();
            show_currency_name();
            if (parseInt($("input[name=from_currency_price]").val()) > 0) {
                request_get_price_of_currency();
            }
        });

        // KeyUp User
        $(document).on("keyup", "input[name=from_currency_price]", function (e) {
            e.preventDefault();
            var v = $(this).val();

            if (parseInt(v.length) > 0 && v !== 0) {
                request_get_price_of_currency();
            }
        });

        // Change ProtoType
        $(document).on("click", "#change_currency_prototype", function (e) {
            e.preventDefault();
            var from = $("select#select2-currency");
            var from_val = $("select#select2-currency").val();
            var to = $("select#select2-currency-to");
            var to_val = $("select#select2-currency-to").val();
            if (to.val() == _rial_id) {
                alert("شما قادر نیستید ارز ریال را به ارز دیگر تبدیل کنید");
                return;
            }

            to.val(from_val).trigger('change');
            from.val(to_val).trigger('change');
            show_currency_name();
            request_get_price_of_currency();
        });

        // Go To Page 2
        $(document).on("click", "#start_change_price", function (e) {
            e.preventDefault();

            var to_val = $("select#select2-currency-to").val();
            if (to_val == _rial_id) {
                $(".select-2-wallet").show();
                $("input[name=wallet]").hide();
            } else {
                $(".select-2-wallet").hide();
                $("input[name=wallet]").show();
            }

            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">بارگذاری...</span>');
            setTimeout(function () {

                $("div[data-step]").hide();
                $("div[data-step=2]").show();

            }, 1500);
        });

        // Go To Page 3
        $(document).on("click", "#go_to_3", function (e) {
            e.preventDefault();
            var to_val = $("select#select2-currency-to").val();
            var wallet = $("input[name=wallet]").val();
            if (to_val != _rial_id && wallet.length < 1) {
                alert("آدرس مقصد را وارد نمایید");
                return;
            }

            // inital page 4
            var from = $("select#select2-currency");
            var to = $("select#select2-currency-to");
            $("[data-step-3-arz-mabda]").html(from.find(':selected').attr('data-persian') + ' - ' + from.find(':selected').attr('data-name'));
            $("[data-step-3-arz-maghsad]").html(to.find(':selected').attr('data-persian') + ' - ' + to.find(':selected').attr('data-name'));
            $("[data-step-3-m-p]").html($("input[name=from_currency_price]").val());
            $("[data-step-3-m-d]").html($("input[name=to_currency_price]").val());
            if (to_val != _rial_id) {
                $("[data-step-3-a-m]").html(wallet);
            } else {
                $("[data-step-3-a-m]").html($("select[name=wallet_select]").val());
            }

            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">بارگذاری...</span>');
            setTimeout(function () {

                $("div[data-step]").hide();
                $("div[data-step=3]").show();

            }, 1500);


        });

        //Go To 4
        $(document).on("click", "#go_to_4", function (e) {
            e.preventDefault();

            var from = $("select#select2-currency");
            var t = 'لطفا مبلغ ';
            t += $("input[name=from_currency_price]").val();
            t += ' ';
            t += from.find(':selected').attr('data-persian');
            t += ' ';
            t += 'را به آدرس مقصد زیر واریز کنید : ';
            $("#variz_kon").html(t);

            $("img#wallet_img_barcode").attr("src", from.find(':selected').attr('data-barcode-image'));
            $("[data-step-4-a-m-k]").html(from.find(':selected').attr('data-modir-wallet'));

            var depozit = from.find(':selected').attr('data-depozit');
            if (depozit.length > 0) {
                $("[data-step-4-a-m-d]").html(from.find(':selected').attr('data-depozit'));
            } else {
                $("p[data-depozit-show], div[data-depozit-show], [data-depozit-show]").hide();
            }


            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">بارگذاری...</span>');
            setTimeout(function () {

                $("div[data-step]").hide();
                $("div[data-step=4]").show();

            }, 1500);

        });

        // Go to 5
        $(document).on("click", "#go_to_5", function (e) {
            e.preventDefault();

            $(this).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">بارگذاری...</span>');
            setTimeout(function () {
                $("div[data-step]").hide();
                $("div[data-step=5]").show();
            }, 1500);
        });

        /**
         * Generate Flat Random Color
         */
        window.wp_price_random_color = function (i = false) {
            let colors = [
                [243, 156, 18, "#f39c12"],
                [52, 152, 219, "#3498db"],
                [192, 57, 43, "#c0392b"],
                [155, 89, 182, "#9b59b6"],
                [39, 174, 96, "#27ae60"],
                [230, 126, 34, "#e67e22"],
                [142, 68, 173, "#8e44ad"],
                [46, 204, 113, "#2ecc71"],
                [41, 128, 185, "#2980b9"],
                [22, 160, 133, "#16a085"],
                [211, 84, 0, "#d35400"],
                [44, 62, 80, "#2c3e50"],
                [241, 196, 15, "#f1c40f"],
                [231, 76, 60, "#e74c3c"],
                [26, 188, 156, "#1abc9c"],
                [46, 204, 113, "#2ecc71"],
                [52, 152, 219, "#3498db"],
                [155, 89, 182, "#9b59b6"],
                [52, 73, 94, "#34495e"],
                [22, 160, 133, "#16a085"],
                [39, 174, 96, "#27ae60"],
                [44, 62, 80, "#2c3e50"],
                [241, 196, 15, "#f1c40f"],
                [230, 126, 34, "#e67e22"],
                [231, 76, 60, "#e74c3c"],
                [236, 240, 241, "#9b9e9f"],
                [149, 165, 166, "#a65d20"]
            ];
            return colors[(i === false ? Math.floor(Math.random() * colors.length) : i)];
        };

        /**
         * Create Line Chart JS
         */
        window.wp_price_line_chart = function (tag_id, title, label, data) {

            // Get Element By ID
            let ctx = document.getElementById(tag_id).getContext('2d');

            // Create Chart
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: label,
                    datasets: data
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    },
                    animation: {
                        duration: 1500,
                    },
                    title: {
                        display: false,
                        text: title
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                },
                plugins: [{
                    afterDraw: function (chart) {
                        if (chart.data.datasets[0].data.every(x => x == 0) === true) {
                            let ctx = chart.chart.ctx;
                            let width = chart.chart.width;
                            let height = chart.chart.height;
                            chart.clear();
                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            ctx.font = "16px normal 'tahoma'";
                            ctx.fillText('No data available', width / 2, height / 2);
                            ctx.restore();
                        }
                    }
                }]
            });
        };

        /**
         * Show Chart
         */
        if (window.wp_price_chart_data_arz_be_arz) {

            let datasets = [];
            let color = window.wp_price_random_color(1);
            datasets.push({
                label: 'مبادلات ارز به ارز',
                data: window.wp_price_chart_data_arz_be_arz,
                backgroundColor: 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',' + '0.3)',
                borderColor: 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',' + '1)',
                borderWidth: 1,
                fill: true
            });

            color = window.wp_price_random_color(2);
            datasets.push({
                label: 'مبادلات ارز به ریال',
                data: window.wp_price_chart_data_arz_be_rial,
                backgroundColor: 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',' + '0.3)',
                borderColor: 'rgba(' + color[0] + ',' + color[1] + ',' + color[2] + ',' + '1)',
                borderWidth: 1,
                fill: true
            });



            window.wp_price_line_chart("wp_stock_price_chart", '', window.wp_price_chart_data_label, datasets);
        }




    }


});