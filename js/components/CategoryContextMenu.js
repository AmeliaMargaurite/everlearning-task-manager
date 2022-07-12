import { getProjectIdFromURL, categoryRequestsURL } from "../helpers.js";

export class CategoryContextMenu extends HTMLElement {
	constructor() {
		super();
	}

	async deleteCategory(project_id, category_id) {
		const response = await fetch(categoryRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "delete_category",
				project_id,
				category_id,
			}),
		}).then((res) => res.json());

		response === "success" ? location.reload() : console.log("fail fail fail");

		this.parentNode.removeChild(this);
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();
		const category_id = this.getAttribute("category_id");

		const div = document.createElement("div");
		div.className = "category-context-menu__wrapper";
		div.onclick = (e) => e.stopPropagation();

		const editBtn = document.createElement("button");
		editBtn.innerHTML = "Edit";
		const categoryInputPopup = document.createElement("category-input-popup");
		categoryInputPopup.setAttribute("category_id", category_id);

		editBtn.onclick = () => document.body.appendChild(categoryInputPopup);

		const deleteBtn = document.createElement("button");
		deleteBtn.innerHTML = "Delete";
		deleteBtn.onclick = () => this.deleteCategory(project_id, category_id);

		div.appendChild(editBtn);
		div.appendChild(deleteBtn);
		this.append(div);

		const closeMenu = (e) => {
			this.parentNode.removeChild(this);
			e.stopPropagation();
			document.removeEventListener("click", closeMenu);
		};

		document.addEventListener("click", closeMenu);
	}
}
