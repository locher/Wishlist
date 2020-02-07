var formDisplay = new Vue({
	el: '#choiceParent',
	data: {
		isDisplay: false
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