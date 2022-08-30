import {
	getProjectIdFromURL,
	taskRequestURL,
} from "../helpers__1661864048886__.js";

export class DaysAllocatedTo extends HTMLElement {
	constructor() {
		super();
	}

	async getAllocatedDays(project_id, task_id) {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({
				request: "get_allocated_days",
				task_id,
				project_id,
			}),
		});

		return await response.json();
	}

	async connectedCallback() {
		const project_id = this.getAttribute("project_id");
		const task_id = this.getAttribute("task_id");
		let allocatedDays = null;

		if (task_id) {
			allocatedDays = await this.getAllocatedDays(project_id, task_id);
		}

		this.innerHTML = `<label>
        Allocated Days
      </label>
      <input type="date" name="days_allocated_to" value="${allocatedDays}"/>`;
	}
}
