function numberType(){
	let num = parseInt(document.getElementById('numberInput').value);
	let sum = 0;
	let result = '';

	if(num < 1) {
		alert("The number must be higher than 0!");
		return 0;
	}

	for (let i = 1; i <= num / 2; i++) {
		if (num % i === 0) {
			sum += i;
		}
	}

	console.log(num + ' ' + sum);
	if (sum < num) {
		result = "Deficient";
	} else if (sum === num) {
		result = "Perfect";
	} else {
		result = "Abundant";
	}

	document.getElementById('result').innerHTML = `<h3> The number ${num} is ${result} </h3>`;
}