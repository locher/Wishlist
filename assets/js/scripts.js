var formDisplay = new Vue({
	el: '#choiceParent',
	data: {
		isDisplay: false
	}
});

var messageAlert = new Vue({
	el: '#delete-confirmation',
	data: {
		isDisplay: false
	},
	methods:{
		reverseDisplay: function(){
			messageAlert.isDisplay = !messageAlert.isDisplay
		}
	}
});

var switchChild = new Vue({
	el: '#switch-child',
	methods:{
		reverseSwitch: function(){
			formDisplay.isDisplay = !formDisplay.isDisplay;
		}
	}
});


//Suppresion cadeau

	// La mododal

	var deleteGift = new Vue({
		el: '#deleteGift',
		data:{
			isDisplay: false,
			giftName: ''
		},
		methods:{
			reverseDisplay: function(){
				this.isDisplay = !this.isDisplay;
			}
		}
	});


	// Le bouton





