const BASE_URL =
	window.location.host === "localhost"
		? window.location.origin + "/task-manager"
		: "";
console.log(window.location.pathname);
export const taskRequestURL = BASE_URL + "/php/queries/task_requests.php";
export const taskFunctionsURL = BASE_URL + "/php/queries/task_functions.php";
export const categoryRequestsURL =
	BASE_URL + "/php/queries/category_requests.php";
export const priorityRequestsURL =
	BASE_URL + "/php/queries/priority_requests.php";
export const noteRequestsURL = BASE_URL + "/php/queries/note_requests.php";
export const noteFunctionsURL = BASE_URL + "/php/queries/note_functions.php";
export const projectFunctionsURL =
	BASE_URL + "/php/queries/project_functions.php";
export const projectRequestsURL =
	BASE_URL + "/php/queries/project_requests.php";

export const statusFunctionsURL =
	BASE_URL + "/php/queries/status_functions.php";
export const statusRequestsURL = BASE_URL + "/php/queries/status_requests.php";

export const getProjectIdFromURL = () => {
	let project_id;
	if (window && window.location) {
		const urlParams = new URLSearchParams(window.location.search);
		project_id = urlParams.get("project_id");
	}

	return project_id;
};

export const closeModal = (modalsId) => {
	const modal = document.getElementById(modalsId);
	const callingModal = modal.parentNode;
	callingModal.parentNode.removeChild(callingModal);
	document.body.style.overflow = "auto";
};

// https://stackoverflow.com/questions/3732046/how-do-you-get-the-hue-of-a-xxxxxx-colour

export const hexToRGB = (hex) => {
	const r = parseInt(hex.substr(1, 2), 16);
	const g = parseInt(hex.substr(3, 2), 16);
	const b = parseInt(hex.substr(5, 2), 16);
	return `${r}, ${g}, ${b}`;
};

export const rgbToHSL = (rgb) => {
	let [r, g, b] = rgb.split(",");
	(r /= 255), (g /= 255), (b /= 255);
	const max = Math.max(r, g, b);
	const min = Math.min(r, g, b);
	let h,
		s,
		l = (max + min) / 2;

	if (max == min) {
		h = s = 0; // achromatic
	} else {
		const d = max - min;
		s = l > 0.5 ? d / (2 - max - min) : d / (max + min);

		switch (max) {
			case r:
				h = (g - b) / d + (g < b ? 6 : 0);
				break;
			case g:
				h = (b - r) / d + 2;
				break;
			case b:
				h = (r - g) / d + 4;
				break;
		}

		h /= 6;
	}

	return [h * 360, s * 100, l * 100];
};

export const hslToHEX = (hsl) => {
	let [h, s, l] = hsl.split(",");
	l /= 100;
	const a = (s * Math.min(l, 1 - l)) / 100;
	const f = (n) => {
		const k = (n + h / 30) % 12;
		const color = l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
		return Math.round(255 * color)
			.toString(16)
			.padStart(2, "0"); //convert to HEX and prefix '0' if needed
	};
	return `#${f(0)}${f(8)}${f(4)}`;
};

export const increaseHueOfHex = (hex) => {
	const rgb = hexToRGB(hex);
	console.log({ rgb });
	let [h, s, l] = rgbToHSL(hexToRGB(hex));
	console.log({ h, s, l });
	if (h >= 340) {
		return hslToHEX(`0, ${s}, ${l}`);
	}

	return hslToHEX(`${h + 20}, ${s}, ${l}`);
};
