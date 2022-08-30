import {
	getProjectIdFromURL,
	categoryRequestsURL,
	increaseHueOfHex,
} from "../helpers__1661861941141__.js";

export class CategoryInputPopup extends HTMLElement {
	constructor() {
		super();
	}

	async saveNewCategory(project_id) {
		const category_name = document.getElementById(
			"new_category_name--input"
		).value;

		const color = document.getElementById("new_category_color--input").value;

		if (category_name) {
			const body = {
				request: "save_new_category",
				project_id,
				category_name,
				color: color ? color : null,
			};

			const response = await fetch(categoryRequestsURL, {
				method: "POST",
				body: JSON.stringify(body),
			});

			const category_id = await response.json();
			const select = document.getElementById("category_select");
			const newOption = document.createElement("option");
			newOption.innerHTML = category_name;
			newOption.value = category_id;
			newOption.selected = true;
			select.appendChild(newOption);
			this.parentNode.removeChild(this);
		}
	}

	async getCategoryData(project_id, category_id) {
		const response = await fetch(categoryRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_category_data",
				project_id,
				category_id,
			}),
		});

		const json = await response.json();
		return json;
	}

	async updateCategoryData(project_id, category_id) {
		const category_name = document.getElementById(
			"new_category_name--input"
		).value;

		const color = document.getElementById("new_category_color--input").value;

		if (category_name && color) {
			const response = await fetch(categoryRequestsURL, {
				method: "POST",
				body: JSON.stringify({
					request: "update_category_data",
					project_id,
					category_id,
					category_name,
					color,
				}),
			});

			location.reload();
		}
	}

	async getCategories(project_id) {
		const response = await fetch(categoryRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_categories",
				project_id,
			}),
		});

		return await response.json();
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();
		const closePopup = () => {
			this.parentNode.removeChild(this);
			document.removeEventListener("click", closePopup);
		};
		const category_id = this.getAttribute("category_id");
		let category_name, category_color;

		if (category_id) {
			const category = await this.getCategoryData(project_id, category_id);
			category_name = category.name;
			category_color = category.color;
		}

		const categories = await this.getCategories(project_id);
		const lastColorAdded = categories
			? categories[Object.keys(categories)[Object.keys(categories).length - 1]]
					.color
			: "#7574bb";
		const suggestedColor = increaseHueOfHex(lastColorAdded);

		this.innerHTML = `
		
		<div class="overlay" id="category_popup--overlay" onclick="closePopup()">
			<div class="save-new-category__wrapper" onclick="event.stopPropagation()">
			<span>	
			<label>Category name</label>
				<input name="category_name" id="new_category_name--input" value="${
					category_name ?? ""
				}"/>
				</span>
				<span>
					<label>Color</label>
					<input name="color" class="category_color_picker" type="color" id="new_category_color--input" value="${
						category_color ? category_color : suggestedColor
					}"/>
					</span>
				<span class="buttons--wrapper">
				<button class="btn" id="category_popup--close-btn" class="btn special">
					Close
				</button>
				<button class="btn" id="category_input--btn" class="btn">
					Save
				</button>
				</span>
			</div>
		</div>
`;
		document.addEventListener("click", closePopup);
		const overlay = document.getElementById("category_popup--overlay");
		overlay.onclick = closePopup;

		const button = document.getElementById("category_input--btn");
		const saveNewOrUpdate = () => {
			if (category_id) {
				return this.updateCategoryData(project_id, category_id);
			} else return this.saveNewCategory(project_id);
		};
		button.onclick = saveNewOrUpdate;

		const closeButton = document.getElementById("category_popup--close-btn");
		closeButton.onclick = closePopup;

		const input = document.getElementById("new_category_name--input");
		input.focus();

		this.className = "add-new-category";
	}
}
