const total = 900900;

for (let i = 1; i <= total / 2; i++) {
    if (total % i === 0) {
        console.log(`${i} * ${total / i}`);
    }
}