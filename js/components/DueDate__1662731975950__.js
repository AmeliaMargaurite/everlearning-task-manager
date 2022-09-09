import {
	getProjectIdFromURL,
	taskRequestURL,
} from "../helpers__1662731975950__.js";

export class DueDate extends HTMLElement {
	constructor() {
		super();
	}

	async getDueDate(project_id, task_id) {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_due_date",
				task_id,
				project_id,
			}),
		});

		return await response.json();
	}

	async connectedCallback() {
		const project_id = this.getAttribute("project_id");
		const task_id = this.getAttribute("task_id");
		let due_date = null;

		if (task_id) {
			console.log("beep");
			due_date = await this.getDueDate(project_id, task_id);
		}

		this.innerHTML = `<label>
        Due date
      </label>
      <input type="date" name="due_date" value="${due_date}"/>`;
	}
}
