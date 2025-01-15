function isHarshad(){
	let num = parseInt(document.getElementById('numberInput').value);
	let result = false;

	result = num % sumOfDigits(num) === 0;

	document.getElementById('result').innerHTML = `<h3> The number ${num} is ${result ? "a Harshad number" : "not a Harshad number"} </h3>`;
}

function sumOfDigits(number) {
	let sum = 0;
	numStr = number.toString();
	for (let n of numStr) {
		sum += parseInt(n, 10);
	}
	return sum;
}