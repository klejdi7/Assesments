const total = 900900;

for (let i = 1; i <= total / 2; i++) {
	if (total % i === 0) {
		document.getElementById('main').innerHTML += `<p>${i} * ${total / i}</p>`;
		console.log(`${i} * ${total / i}`);
	}
}