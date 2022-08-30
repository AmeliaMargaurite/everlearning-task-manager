import {
	getProjectIdFromURL,
	categoryRequestsURL,
} from "../helpers__1661869629422__.js";

export class CategoryDropdown extends HTMLElement {
	constructor() {
		super();
	}

	async getCategoriesData(project_id, task_id) {
		const response = await fetch(categoryRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_dropdown_categories",
				project_id,
				task_id,
			}),
		});

		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const project_id = this.getAttribute("project_id");
		const task_id = this.getAttribute("task_id");
		const categoryData = await this.getCategoriesData(project_id, task_id);
		const label = document.createElement("label");
		label.innerText = "Category";
		label.className = "category";

		const i = document.createElement("div");
		i.className = "icon plus";
		i.onclick = () => {
			const categoryInputPopup = document.createElement("category-input-popup");
			document.body.appendChild(categoryInputPopup);
		};

		label.appendChild(i);

		// SELECT ELEMENT
		const select = document.createElement("select");
		select.className = "categories";
		select.name = "category";
		select.id = "category_select";

		let categories = '<option value="">Select category</option>';

		// OPTION ELEMENTS
		for (let id in categoryData) {
			const category = categoryData[id];
			const selected = category?.this_task ? "selected" : "";
			categories += `<option value="${id}" ${selected}>${category.name}</option>`;
		}
		select.innerHTML = categories;

		this.appendChild(label);
		this.appendChild(select);
	}
}
