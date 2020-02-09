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

