(function () {
    if (typeof jQuery !== "undefined" && typeof $.fn.daterangepicker !== "undefined") {
        var datepickers = [].slice.call(document.querySelectorAll('[data-kt-daterangepicker="true"]'));

        var startDate = moment().subtract(11, "months").startOf("month"); // Start date for last 11 months plus current month
        var endDate = moment().endOf("month"); // End date for the current month

        datepickers.map(function (datepicker) {
            if (datepicker.getAttribute("data-kt-initialized") !== "1") {
                var displayElement = datepicker.querySelector("div");
                var opens = datepicker.getAttribute("data-kt-daterangepicker-opens") || "left";

                var handleDateSelection = function (start, end) {
                    if (displayElement) {
                        displayElement.innerHTML = start.isSame(end, "day")
                            ? start.format("D MMM YYYY")
                            : start.format("D MMM YYYY") + " - " + end.format("D MMM YYYY");
                        // Call your function here with the selected date range
                        handleDateRangeChange(start, end);
                    }
                };

                $(datepicker).daterangepicker(
                    {
                        autoUpdateInput: false, // Disable auto update input to handle it manually in callback
                        opens: opens,
                        ranges: {
                            Lifetime: [
                                moment().subtract(11, "months").startOf("month"),
                                moment().endOf("month")
                            ],
                            Today: [
                                moment().startOf("day"),
                                moment().endOf("day")
                            ],
                            "This Week": [
                                moment().startOf("week"),
                                moment().endOf("week")
                            ],
                            "This Month": [
                                moment().startOf("month"),
                                moment().endOf("month")
                            ]
                        }
                    },
                    handleDateSelection
                );

                // Set initial empty state or placeholder
                if (displayElement) {
                    displayElement.innerHTML = "Select a date range";
                }

                datepicker.setAttribute("data-kt-initialized", "1");
            }
        });
    }
})();

var renderChartNew = null;
var renderVendorEarningsChartNew = null;
var renderPaymentModeChartNew = null;
var renderItemCategorySellsChartNew = null;
var renderChartEarningProfitNew = null;
var renderChartProfitExpencesNew = null;
var renderChartTopOrderedItemNew = null;
var renderChartTopPlayAreaBookingNew = null;

function renderChart(labels, earnings, revenues, expenses) {
    var chartElement = document.querySelector("#vendor_payout_report");
    var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
    var primaryColor = KTUtil.getCssVariableValue("--bs-primary");
    var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
    var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
    var gray300 = KTUtil.getCssVariableValue("--bs-gray-300");
    var dangerColor = KTUtil.getCssVariableValue("--bs-danger");

    if (renderChartNew) {
        renderChartNew.destroy();
    }

    renderChartNew = new ApexCharts(chartElement, {
        series: [
            {
                name: "Net earning",
                data: earnings,
            },
            {
                name: "Expenses",
                data: expenses,
            },
            {
                name: "Gross Earning",
                data: revenues,
            },
        ],
        chart: {
            fontFamily: "inherit",
            type: "bar",
            height: chartHeight,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "40%",
                borderRadius: 6,
            },
        },
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            opacity: 1,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        colors: [primaryColor, dangerColor, gray300],
        grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderChartNew.render();
}

function renderVendorEarningsChart(labels, earnings) {
    var chartElement = document.querySelector("#top_vendor_earnings");
    var height = 250;
    var labelColor = KTUtil.getCssVariableValue("--bs-gray-500");
    var borderColor = KTUtil.getCssVariableValue("--bs-gray-200");
    var baseColor = KTUtil.getCssVariableValue("--bs-warning");
    var secondaryColor = KTUtil.getCssVariableValue("--bs-gray-300");

    if (renderVendorEarningsChartNew) {
        renderVendorEarningsChartNew.destroy();
    }

    renderVendorEarningsChartNew = new ApexCharts(chartElement, {
        series: [
            {
                name: "Earnings",
                data: earnings,
            },
        ],
        chart: {
            fontFamily: "inherit",
            type: "bar",
            height: height,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: true,
                columnWidth: ["30%"],
                endingShape: "rounded",
                borderRadius: 7,
            },
        },
        legend: {
            show: true,
            position: "top",
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: labelColor,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            opacity: 1,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        colors: [baseColor, secondaryColor],
        grid: {
            borderColor: borderColor,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderVendorEarningsChartNew.render();
}

function renderPaymentModeChart(labels, series) {
    var chartElement = document.querySelector("#payment_mode_chart");
    var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
    var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
    var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
    var primaryColor = KTUtil.getCssVariableValue("--bs-primary");
    var secondaryColor = KTUtil.getCssVariableValue("--bs-secondary");
    var infoColor = KTUtil.getCssVariableValue("--bs-info");
    var warningColor = KTUtil.getCssVariableValue("--bs-warning");
    var dangerColor = KTUtil.getCssVariableValue("--bs-danger");
    var successColor = KTUtil.getCssVariableValue("--bs-success");

    if (renderPaymentModeChartNew) {
        renderPaymentModeChartNew.destroy();
    }

    renderPaymentModeChartNew = new ApexCharts(chartElement, {
        series: series,
        chart: {
            fontFamily: "inherit",
            type: "area",
            height: 300,
            toolbar: {
                show: false,
            },
        },
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 3,
            curve: "smooth",
            colors: [
                primaryColor,
                secondaryColor,
                dangerColor,
                infoColor,
                warningColor,
                successColor,
            ],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            type: "solid",
            opacity: 0.35,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
        colors: [
            primaryColor,
            secondaryColor,
            dangerColor,
            infoColor,
            warningColor,
            successColor,
        ],
        grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderPaymentModeChartNew.render();
}

function renderItemCategorySellsChart(labels, series) {
    var chartElement = document.querySelector("#item_category_sells_chart");
    var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
    var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
    var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
    var primaryColor = KTUtil.getCssVariableValue("--bs-primary");
    var secondaryColor = KTUtil.getCssVariableValue("--bs-secondary");
    var infoColor = KTUtil.getCssVariableValue("--bs-info");
    var warningColor = KTUtil.getCssVariableValue("--bs-warning");
    var dangerColor = KTUtil.getCssVariableValue("--bs-danger");
    var successColor = KTUtil.getCssVariableValue("--bs-success");

    if (renderItemCategorySellsChartNew) {
        renderItemCategorySellsChartNew.destroy();
    }

    renderItemCategorySellsChartNew = new ApexCharts(chartElement, {
        series: series,
        chart: {
            fontFamily: "inherit",
            type: "bar",
            height: 325,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "5px",
                borderRadius: 6,
            },
        },
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            opacity: 1,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        colors: [
            primaryColor,
            secondaryColor,
            dangerColor,
            infoColor,
            warningColor,
            successColor,
        ],
        grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderItemCategorySellsChartNew.render();
}

function renderChartEarningProfit(labels, earnings, profit, vendorEarnings) {
    var chartElement = document.querySelector("#earning_profit_report");
    var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
    var primaryColor = KTUtil.getCssVariableValue("--bs-success");
    var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
    var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
    var gray300 = KTUtil.getCssVariableValue("--bs-gray-300");
    var dangerColor = KTUtil.getCssVariableValue("--bs-warning");
    var infoColor = KTUtil.getCssVariableValue("--bs-info");

    if (renderChartEarningProfitNew) {
        renderChartEarningProfitNew.destroy();
    }

    renderChartEarningProfitNew = new ApexCharts(chartElement, {
        series: [
            {
                name: "Total Earning",
                data: earnings,
            },
            {
                name: "Profit",
                data: profit,
            },
            {
                name: "Vendor Earning",
                data: vendorEarnings,
            },
        ],
        chart: {
            fontFamily: "inherit",
            type: "bar",
            height: chartHeight,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "40%",
                borderRadius: 6,
            },
        },
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            opacity: 1,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        colors: [primaryColor, dangerColor, infoColor],
        grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderChartEarningProfitNew.render();
}

function renderChartProfitExpences(labels, profit, expenses) {
    var chartElement = document.querySelector("#profit_expences_report");
    var chartHeight = parseInt(KTUtil.css(chartElement, "height"));
    var primaryColor = KTUtil.getCssVariableValue("--bs-warning");
    var gray500 = KTUtil.getCssVariableValue("--bs-gray-500");
    var gray200 = KTUtil.getCssVariableValue("--bs-gray-200");
    var gray300 = KTUtil.getCssVariableValue("--bs-gray-300");
    var dangerColor = KTUtil.getCssVariableValue("--bs-danger");

    if (renderChartProfitExpencesNew) {
        renderChartProfitExpencesNew.destroy();
    }

    renderChartProfitExpencesNew = new ApexCharts(chartElement, {
        series: [
            {
                name: "Profit",
                data: profit,
            },
            {
                name: "Expenses",
                data: expenses,
            },
        ],
        chart: {
            fontFamily: "inherit",
            type: "bar",
            height: chartHeight,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "40%",
                borderRadius: 6,
            },
        },
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ["transparent"],
        },
        xaxis: {
            categories: labels,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        yaxis: {
            labels: {
                style: {
                    colors: gray500,
                    fontSize: "12px",
                },
            },
        },
        fill: {
            opacity: 1,
        },
        states: {
            normal: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            hover: {
                filter: {
                    type: "none",
                    value: 0,
                },
            },
            active: {
                allowMultipleDataPointsSelection: false,
                filter: {
                    type: "none",
                    value: 0,
                },
            },
        },
        tooltip: {
            style: {
                fontSize: "12px",
            },
            y: {
                formatter: function (val) {
                    return "₹" + val;
                },
            },
        },
        colors: [primaryColor, dangerColor, gray300],
        grid: {
            borderColor: gray200,
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
    });
    renderChartProfitExpencesNew.render();
}

function renderChartTopOrderedItem(initialDatas) {
    var chartElement = document.querySelector("#top_order_item_pie");

    var itemNames = initialDatas.map((item) => item.name);
    var itemCounts = initialDatas.map((item) => parseInt(item.count));

    if (renderChartTopOrderedItemNew) {
        renderChartTopOrderedItemNew.destroy();
    }

    var options = {
        series: itemCounts,
        chart: {
            fontFamily: "inherit",
            type: "donut",
            width: 350,
        },
        plotOptions: {
            pie: {
                donut: {
                    size: "50%",
                    labels: {
                        value: {
                            fontSize: "10px",
                        },
                    },
                },
            },
        },
        colors: [
            KTUtil.getCssVariableValue("--bs-success"),
            KTUtil.getCssVariableValue("--bs-danger"),
            KTUtil.getCssVariableValue("--bs-info"),
            KTUtil.getCssVariableValue("--bs-primary"),
            KTUtil.getCssVariableValue("--bs-warning"),
            KTUtil.getCssVariableValue("--bs-secondary"),
        ],
        stroke: {
            width: 0,
        },
        labels: itemNames,
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
                width: "10px"
            },
        },
        fill: {
            type: "false",
        },
    };

    renderChartTopOrderedItemNew = new ApexCharts(chartElement, options); // Update the global chart variable
    renderChartTopOrderedItemNew.render();
}

function renderChartTopBookedPlayArea(initialDatas) {
    var chartElement = document.querySelector("#top_booked_play_area_pie");

    var itemNames = initialDatas.map((item) => item.name);
    var itemCounts = initialDatas.map((item) => parseInt(item.count));

    if (renderChartTopPlayAreaBookingNew) {
        renderChartTopPlayAreaBookingNew.destroy();
    }

    var options = {
        series: itemCounts,
        chart: {
            fontFamily: "inherit",
            type: "donut",
            width: 350,
        },
        plotOptions: {
            pie: {
                donut: {
                    size: "50%",
                    labels: {
                        value: {
                            fontSize: "10px",
                        },
                    },
                },
            },
        },
        colors: [
            KTUtil.getCssVariableValue("--bs-success"),
            KTUtil.getCssVariableValue("--bs-danger"),
            KTUtil.getCssVariableValue("--bs-info"),
            KTUtil.getCssVariableValue("--bs-primary"),
            KTUtil.getCssVariableValue("--bs-warning"),
            KTUtil.getCssVariableValue("--bs-secondary"),
        ],
        stroke: {
            width: 0,
        },
        labels: itemNames,
        legend: {
            show: true,
            labels: {
                colors: KTUtil.getCssVariableValue("--bs-gray-500"),
            },
        },
        fill: {
            type: "false",
        },
    };

    renderChartTopPlayAreaBookingNew = new ApexCharts(chartElement, options); // Update the global chart variable
    renderChartTopPlayAreaBookingNew.render();
}

function handleDateRangeChange(start, end) {
    $.ajax({
        url: "/dashboard/get-all-data-chart", // Your Laravel route
        type: "GET",
        dataType: "json",
        data: {
            start_date: start.format("YYYY-MM-DD HH:mm:ss"),
            end_date: end.format("YYYY-MM-DD HH:mm:ss"),
        },
        success: function (response) {
            renderChart(
                response.labels,
                response.earnings,
                response.revenues,
                response.expenses
            );
            renderVendorEarningsChart(
                response.vendorNamesAsLabel,
                response.VendorEarningsArray
            );
            renderPaymentModeChart(
                response.paymentModeLabels,
                response.paymentModeSeries
            );
            renderItemCategorySellsChart(
                response.categorySellsLabels,
                response.categorySellsSeries
            );
            renderChartEarningProfit(
                response.labels,
                response.earnings,
                response.profit,
                response.vendorEarnings
            );
            renderChartProfitExpences(
                response.labels,
                response.profit,
                response.expenses
            );
            renderChartTopOrderedItem(response.topOrderedItemsData);
            renderChartTopBookedPlayArea(response.topBookedPlayAreaData);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching data:", error);
        },
    });
}
