var calculator = {
	init: function (settings) {
		calculator.config = {
			getUrl: 'http://52918-add-load-price.portfolio.stag.stfalcon.com/uploads/files/price.json',
			postUrl: 'http://52918-add-load-price.portfolio.stag.stfalcon.com/order',
			platformItems: 'input[name=platform]',
			tabActiveClass: 'chip-tabs__item--active',
			featuresScreen: '.calculator__chips_features',
			checkboxList: '.chips__list',
			popup: '.calculator__popup',
			checkboxItems: '.chip__feature',
			form: '.calculator__form',
			sumContainers: {
				total: '.calc-card__value-total',
				be: '.calc-card__value-be',
				design: '.calc-card__value-design',
				mobile: '.calc-card__value-mobile',
				qa: '.calc-card__value-qa',
				pm: '.calc-card__value-pm'
			},
			cardToggleBlock: '.calc-card__top',
			cardClass: '.calculator__popup',
			toggleClassName: 'calculator__popup--open-mobile'
		};

		$.extend(calculator.config, settings);
		calculator.setup();
	},

	initialSum: {
		be: 0,
		design: 0,
		mobile: 0,
		qa: 0,
		pm: 0
	},

	state: {
		platform: '',
		featureList: [],
		selectedFeatures: [],
		sum: this.initialSum,
		total: 0,
		android: [],
		ios: [],
		android_ios: [],
		formData: {},
		cardStaticPos: false
	},

	setup: function () {
		let self = this;

		// get json and reduce it

		$.get(self.config.getUrl)
			.done(function (data) {
				self.state.featureList = data;
				self.state.android = self.getByPlatform("android");
				self.state.ios = self.getByPlatform("ios");
				self.state.android_ios = self.getByPlatform("android_ios");

				for (let i = 0; i < self.state.featureList.length; i++) {
					let newCheckbox = self.createCheckbox(data[i]);

					$(self.config.checkboxList).append(newCheckbox);
				}
			})
			.fail(function () {
				alert("Something was wrong. Please reload the page or try again later");
			});

		// select platform in radio buttons

		$(self.config.platformItems).on('change', function () {
			self.state.platform = $(self.config.platformItems + ':checked').val();

			$(self.config.featuresScreen).show();

			self.selectFeatures();
		});

		// select features in checkboxes

		$('body').on('change', self.config.checkboxItems, function () {
			$(self.config.popup).show();

			self.selectFeatures();
		});

		// submit form

		$(self.config.form).on('submit', function (e) {
			e.preventDefault();

			let email = $(this).find('input[type=email]').val();
			let formData = {
				"email": email,
				"platform": self.state.platform,
				"order": self.state.selectedFeatures.map(function (item) {
					return {
						"name": item.name
					}
				})
			};

			$.ajax({
				url: self.config.postUrl,
				type: "POST",
				data: JSON.stringify(formData),
				contentType: "application/json; charset=utf-8",
				crossDomain: true,
				dataType: "json",
			}).done(function () {
				alert("Pdf is sent. Check your email, please");
			})
				.fail(function () {
					alert("Something was wrong. Please reload the page or try again later");
				});
		});

		// open mobile card block

		$(self.config.cardToggleBlock).on('click', function () {
			$(self.config.cardClass).toggleClass(self.config.toggleClassName);
		});
	},

	getByPlatform(platform) {
		let self = this;
		let plat = self.state.featureList.map(function (p) {
			return {
				name: p.name,
				price: p.price[platform]
			}
		});

		return plat.reduce(function (acc, current) {
			return {
				...acc,
				[current.name]: {...current, platform},
			}
		}, {})
	},

	createCheckbox(item) {
		return '<label class="chip__label">' +
			'<input type="checkbox" class="chip__feature" value=' + item.name + '>' +
			'  <span class="chip__label-text">' +
			item.title +
			'  </span>' +
			'</label>'
	},

	selectFeatures() {
		let self = this;

		self.state.selectedFeatures = [];

		$(self.config.checkboxItems + ':checked').each(function () {
			let checkVal = $(this).val();
			const feature = self.state[self.state.platform][checkVal];

			self.state.selectedFeatures.push(feature);
		});

		self.calculateSum();
	},

	calculateSum() {
		let self = this;
		let selectedFeatures = self.state.selectedFeatures;

		if (!selectedFeatures.length) {
			self.state.sum = self.initialSum;
			self.state.total = 0;
			self.updateView();

			return
		}

		self.state.sum = selectedFeatures.reduce(function (acc, {price}) {
			const before = {...acc};
			return {
				...acc,
				...Object.entries(price).reduce(function (acc, current) {
					return {
						...acc,
						[current[0]]: before[current[0]] ? +before[current[0]] + +current[1] : +current[1]
					}
				}, {})
			}
		}, {});

		self.state.total = Object.values(self.state.sum).reduce((a, b) => a + b);

		self.updateView();
	},

	updateView: function () {
		let self = this;

		// TODO: simplify this

		$(self.config.sumContainers.total).text(self.addPriceSpace(self.state.total));
		$(self.config.sumContainers.be).text(self.addPriceSpace(self.state.sum.be));
		$(self.config.sumContainers.design).text(self.addPriceSpace(self.state.sum.design));
		$(self.config.sumContainers.mobile).text(self.addPriceSpace(self.state.sum.mobile));
		$(self.config.sumContainers.qa).text(self.addPriceSpace(self.state.sum.qa));
		$(self.config.sumContainers.pm).text(self.addPriceSpace(self.state.sum.pm));
	},

	addPriceSpace: function (val) {
		return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
	}
};

$(document).ready(function () {
	calculator.init();

	// uncomment if fixed block in IE-11 will be needed

	// let $calcCard = $('.calc-card');
	// let calcCardOffset = $calcCard.offset().top;
	// $(window).scroll(function () {
	// 	if ($(window).width() < 1023) return;
	//
	// 	if ($(this).scrollTop() > calcCardOffset - 20) {
	// 		$calcCard.addClass('calc-card--fixed');
	// 	} else {
	// 		$calcCard.removeClass('calc-card--fixed');
	// 	}
	// });
});
