import { priorityRequestsURL } from "../helpers__1661519568159__.js";

export class PriorityDropdown extends HTMLElement {
	constructor() {
		super();
	}

	async getPriorityOptions(task_id) {
		const response = await fetch(priorityRequestsURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_all_priorities", task_id }),
		});
		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const task_id = this.getAttribute("task_id");
		const priorityData = await this.getPriorityOptions(task_id);
		const label = document.createElement("label");
		label.innerHTML = "Priority";
		label.className = "priority";

		// SELECT ELEMENT

		const select = document.createElement("select");
		select.className = "priorities";
		select.name = "priority";
		select.id = "priority_select";

		let priorities = '<option value="">Select priority</option>';

		// OPTION ELEMENTS

		for (let id in priorityData) {
			const priority = priorityData[id];
			const selected = priority?.this_task ? "selected" : "";
			priorities += `<option value="${id}" ${selected}>${priority.name}</option>`;
		}

		select.innerHTML = priorities;

		this.appendChild(label);
		this.appendChild(select);
	}
}
