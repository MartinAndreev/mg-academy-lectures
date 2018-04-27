function Documents(element) {

    /**
     * The form element
     * @type {jQuery}
     */
    this.form = element;

    /**
     * The add button
     * @type {jQuery}
     */
    this.addButton = element.find('.document-add-row');

    /**
     * Stores the goods row template. Clones the initial row and removes if from dom
     * @type {null}
     */
    this.goodsRowTemplate = null;

    /**
     * Stores the product goods rows
     * @type {Array}
     */
    this.goods = [];

    this.total = 0;
    this.vatTotal = 0;
    this.baseTotal = 0;
    this.vatRate = 0;

    return this.init();
}

/**
 * Initis the plugin
 * @returns {Documents}
 */
Documents.prototype.init = function () {
    this
            .initGoodsRowTemplate()
            .loadGoods()
            .initEventsListeners()
            .initEvents();
    
    this.form.trigger('documents/goods/render');

    return this;
};

/**
 * Inits the events listeners
 * @returns {Documents}
 */
Documents.prototype.initEventsListeners = function () {
    var self = this;
    this.form.on('documents/goods/add', function () {
        self.addRow();
    });

    this.form.on('documents/goods/render', function () {
        self.renderGoods();
    });

    return this;
};

Documents.prototype.initEvents = function () {
    var self = this;
    this.addButton.on('click', function () {
        self.form.trigger('documents/goods/add');
    });

    this.form.find('#document-total-vat-percent').on('change', function () {
        self.vatRate = parseInt($(this).val());
        self.form.trigger('documents/goods/render');
    });
    return this;
};

Documents.prototype.addRow = function () {
    this.goods.push({
        good: '',
        good_id: 0,
        good_quantity: 0,
        good_price: 0,
        good_discount_percent: 0,
        good_discount_fixed: 0,
        good_total: 0,
        discount_based_on: 'fixed',
    });

    this.renderGoods();

    return this;
};

Documents.prototype.renderGoods = function () {
    this.form.find('.goods-row').remove();

    for (var i = 0; i < this.goods.length; i++) {
        var good = this.goods[i];
        this.renderRow(good, i);
    }

    this.renderTotals();

    return this;
};

Documents.prototype.renderTotals = function () {
    this.total = 0;

    for (var i = 0; i < this.goods.length; i++) {
        this.total += Number(this.goods[i].good_total);
    }

    this.vatTotal = Number((this.total * (this.vatRate / 100)).toFixed(2));
    this.baseTotal = Number((this.total - this.vatTotal).toFixed(2));

    this.form.find('#document-total-vat').text(this.vatTotal.toFixed(2));
    this.form.find('#document-total-no-vat').text(this.baseTotal.toFixed(2));
    this.form.find('#document-total').text(this.total.toFixed(2));
};

Documents.prototype.renderRow = function (good, index) {
    console.log(row);
    var row = this.goodsRowTemplate.clone();
    var tabIndex = (index + 1) * 10;

    row.data('index', index);

    row.find('[name]').each(function () {
        var name = $(this).attr('name');
        name = name.replace('##', index);
        $(this).attr('name', name);
        $(this).attr('tabindex', tabIndex);
        tabIndex++;
    });

    // Calc the total
    good.good_total = good.good_price * good.good_quantity;

    if (good.discount_based_on == 'fixed') {
        good.good_discount_percent = (good.good_discount_fixed == 0) ? 0 : (1 - ((good.good_total - good.good_discount_fixed) / good.good_total)) * 100;
        good.good_discount_percent = Number(good.good_discount_percent.toFixed(2));
    } else {
        good.good_discount_fixed = (good.good_discount_percent == 0) ? 0 : good.good_total * (good.good_discount_percent / 100);
        good.good_discount_fixed = Number(good.good_discount_fixed.toFixed(2));
    }

    good.good_total = parseFloat(good.good_total - good.good_discount_fixed).toFixed(2);

    row.find('input[name*="id"]').val(good.good_id);
    row.find('input[name*="name"]').val(good.good);
    row.find('input[name*="price"]').val(good.good_price.toFixed(2));
    row.find('input[name*="quantity"]').val(good.good_quantity);
    row.find('input[name*="discount_percent"]').val(good.good_discount_percent.toFixed(2));
    row.find('input[name*="discount_ammount"]').val(good.good_discount_fixed.toFixed(2));
    row.find('.goods-row-total').find('span').text(good.good_total);
    row.find('.goods-row-number').find('span').text(index + 1);

    this.initGoodRowActions(row, index);

    this.form
            .find('.document-goods-row')
            .find('tbody')
            .append(row);

    return this;
};

Documents.prototype.initGoodRowActions = function (row, index) {
    var self = this;
    row.find('.goods-row-price').find('input').on('change', function () {
        var price = ($(this).val() == '') ? 0 : $(this).val();
        self.goods[index].good_price = Number(parseFloat(price).toFixed(2));
        self.form.trigger('documents/goods/render');
    });

    row.find('.goods-row-quantity').find('input').on('change', function () {
        console.log(self.goods[index]);
        var quantity = ($(this).val() == '') ? 0 : $(this).val();
        self.goods[index].good_quantity = parseInt(quantity);
        self.form.trigger('documents/goods/render');
    });

    row.find('.goods-row-discount-percent').find('input').on('change', function () {
        var discount = ($(this).val() == '') ? 0 : $(this).val();
        self.goods[index].good_discount_percent = Number(parseFloat(discount).toFixed(2));
        self.goods[index].discount_based_on = 'percent';
        self.form.trigger('documents/goods/render');
    });

    row.find('.goods-row-discount-ammount').find('input').on('change', function () {
        var discount = ($(this).val() == '') ? 0 : $(this).val();
        self.goods[index].good_discount_fixed = Number(parseFloat(discount).toFixed(2));
        self.goods[index].discount_based_on = 'fixed';
        self.form.trigger('documents/goods/render');
    });

    row.find('.goods-autocomplete').autocomplete({
        source: ajax.url + 'goods/find',
        select: function (event, ui) {
            self.goods[index].good_id = ui.item.id;
            self.goods[index].good = ui.item.name;
            self.goods[index].good_price = parseFloat(ui.item.price);
            self.form.trigger('documents/goods/render');
        }
    });
};

/**
 * Loads the goods
 * @returns {Documents}
 */
Documents.prototype.loadGoods = function () {
    var self = this;
    this.vatRate = parseInt(this.form
            .find('#document-total-vat-percent')
            .val()
            );

    var initalRolls = this.form
            .find('.document-goods-row')
            .find('tbody')
            .find('.goods-row')
            .not('.goods-initial-row');

    if (initalRolls.length > 0) {
        initalRolls.each(function () {
            var good = {
                good: $(this).find('input[name*="name"]').val(),
                good_id: parseInt($(this).find('input[name*="id"]').val()),
                good_quantity: parseInt($(this).find('input[name*="quantity"]').val()),
                good_price: Number(parseFloat($(this).find('input[name*="price"]').val()).toFixed(2)),
                good_discount_percent: Number(parseFloat($(this).find('input[name*="discount_percent"]').val()).toFixed(2)),
                good_discount_fixed: Number(parseFloat($(this).find('input[name*="discount_ammount"]').val()).toFixed(2)),
                good_total: 0,
                discount_based_on: 'fixed',
            };

            self.goods.push(good);
        });
    }

    return this;
};

/**
 * Inits the goods row template
 * @returns {Documents}
 */
Documents.prototype.initGoodsRowTemplate = function () {
    var row = this.form.find('.goods-row.goods-initial-row').clone();
    row.removeClass('.goods-initial-row');
    this.tabIndex = parseInt(row.find('.goods-row-name-input').attr('tabindex'));

    this.goodsRowTemplate = row;

    this.form.find('.goods-row.goods-initial-row').remove();

    return this;
};

$(document).ready(function () {
    $('.datepicker').datepicker({
        format: 'dd.mm.yyyy'
    });

    var document = new Documents($('#document-form'));

    $('#customer-name-input').autocomplete({
        source: ajax.url + 'customers/find',
        select: function (event, ui) {
            $('#customer-id-input').val(ui.item.id);
            $('#customer-vat-input').val(ui.item.bulstat);
            $('#customer-city-input').val(ui.item.city);
            $('#customer-country-input').val(ui.item.country);
            $('#customer-address-input').val(ui.item.address);
            $('#customer-mol-input').val(ui.item.mol);
        }
    });
});