const allLis = Array.from(document.querySelectorAll(".dates li"));
const allDays = Array.from(document.querySelectorAll(".dates li:not(.mask)"));
const weekNavBtns = document.querySelectorAll(".week");

const navWeek = (type) => {
	const firstLiOpenIndex = allLis.findIndex((x) =>
		x.classList.contains("open")
	);
	const lastLiOpenIndex = firstLiOpenIndex + 7;

	if (type === "next") {
		if (allLis.length - 1 - lastLiOpenIndex > 0) {
			allLis.forEach((x) => x.classList.remove("open"));

			for (let i = lastLiOpenIndex; i < lastLiOpenIndex + 7; i++) {
				if (allLis[i]) {
					allLis[i].classList.add("open");
				} else return;
			}
		}
	} else if (type === "previous") {
		if (firstLiOpenIndex !== 0) {
			allLis.forEach((x) => x.classList.remove("open"));

			const newFirstLiOpenIndex = firstLiOpenIndex - 1;

			for (let i = newFirstLiOpenIndex; i > newFirstLiOpenIndex - 7; i--) {
				if (allLis[i]) {
					allLis[i].classList.add("open");
				} else return;
			}
		}
	}
};

weekNavBtns.forEach((btn) => (btn.onclick = () => navWeek(btn.dataset.type)));
