import {
	getProjectIdFromURL,
	statusRequestsURL,
	taskRequestURL,
} from "../helpers__1661864048886__.js";

export class TileContextMenu extends HTMLElement {
	constructor() {
		super();
	}

	async getStatuses(project_id) {
		const response = await fetch(statusRequestsURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_statuses", project_id }),
		});
		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();
		const task_id = this.getAttribute("task_id");
		const currentStatus = this.getAttribute("current_status");
		const statuses = await this.getStatuses(project_id);
		let options = ``;

		for (let id in statuses) {
			const status = statuses[id];
			const checked = currentStatus === status.name ? "checked" : "";
			const name = status.name === "incomplete" ? "todo" : status.name;
			const element = `
        <input type="radio" name="status" id="${name}" value="${status.name}" ${checked}/>
          <label for="${name}">${status.name}
        </label>
      `;
			options += element;
		}

		const form = `
    <form action="${taskRequestURL}" method="POST" id="modal-form" onclick="event.stopPropagation()">
        ${options}
      <input type="hidden" name="update_task_status" value="${project_id}"/>
      <input type="hidden" name="task_id" value="${task_id}"/>
    </form>
    `;

		this.innerHTML = `<modal-no-buttons id="tile-context-menu"></modal-no-buttons>`;

		const modal = document.getElementById("modal--contents");
		modal.innerHTML = form;
		modal.parentNode.classList.add("statuses");

		const inputs = document.querySelectorAll('input[name="status"]');
		const modalForm = document.getElementById("modal-form");
		console.log(modalForm);
		for (const input of inputs) {
			input.onclick = () => modalForm.submit();
		}
	}
}
