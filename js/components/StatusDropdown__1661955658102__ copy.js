import {
	getProjectIdFromURL,
	statusRequestsURL,
} from "../helpers__1661955658102__.js";

export class StatusDropdown extends HTMLElement {
	constructor() {
		super();
	}

	async getStatusesData(project_id, task_id) {
		const response = await fetch(statusRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_dropdown_statuses",
				project_id,
				task_id,
			}),
		});

		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const { project_id, task_id } = this.dataset;

		const statusData = await this.getStatusesData(project_id, task_id);
		console.log(statusData);
		const label = document.createElement("label");
		label.innerText = "Status";
		label.className = "status";

		// SELECT ELEMENT
		const select = document.createElement("select");
		select.className = "statuses";
		select.name = "status";
		select.id = "status_select";

		let statuses = '<option value="">Select status</option>';

		// OPTION ELEMENTS
		for (let id in statusData) {
			const status = statusData[id];
			const selected = status?.this_task ? "selected" : "";
			statuses += `<option value="${id}" ${selected}>${status.name}</option>`;
		}
		select.innerHTML = statuses;

		this.appendChild(label);
		this.appendChild(select);
	}
}
