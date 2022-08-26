import {
	taskFunctionsURL,
	taskRequestURL,
} from "../helpers__1661520306754__.js";

export class EditTodaysTasksDialog extends HTMLElement {
	constructor() {
		super();
	}

	async getAllProjectsData() {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_all_data_for_todays_tasks" }),
		});
		const json = await response.json();
		return await json;
	}

	async toggleTodayTaskStatus(e, task_id, project_id) {
		const checked = e.target.checked;

		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({
				request: "toggle_on_todays_task",
				task_id,
				project_id,
				checked,
			}),
		});
	}

	async connectedCallback() {
		const allProjects = await this.getAllProjectsData();

		// UI
		const form = document.createElement("form");
		form.action = taskFunctionsURL;
		form.method = "POST";
		form.className = "todays-tasks";
		form.id = "modal-form";

		const projectKeys = Object.keys(allProjects);

		for (let i = 0, n = projectKeys.length; i < n; i++) {
			const project = allProjects[projectKeys[i]];
			const project_id = projectKeys[i];
			const title = document.createElement("h5");
			title.innerHTML = project.name;
			const wrapper = document.createElement("span");
			wrapper.append(title);
			const ul = document.createElement("ul");
			const tasks = project?.tasks;
			if (tasks) {
				console.log({ tasks });
				const tasksKeys = Object.keys(tasks);

				for (let j = 0, m = tasksKeys.length; j < m; j++) {
					const task = tasks[tasksKeys[j]];
					const li = document.createElement("li");
					const checkbox = document.createElement("input");
					checkbox.type = "checkbox";
					checkbox.checked = task.todays_task === 1;
					checkbox.onchange = (e) =>
						this.toggleTodayTaskStatus(e, task.task_id, project_id);
					const label = document.createElement("label");
					label.appendChild(checkbox);
					const p = document.createElement("p");

					p.innerText = task.name;
					label.appendChild(p);
					li.appendChild(label);
					ul.append(li);
				}
			} else {
				const p = document.createElement("p");
				p.innerText = "No active tasks";
				ul.append(p);
			}

			wrapper.append(ul);
			form.append(wrapper);
		}

		const hiddenInput = document.createElement("input");
		hiddenInput.type = "hidden";
		hiddenInput.name = "toggle_todays_tasks";
		form.appendChild(hiddenInput);

		this.innerHTML = `<modal-popup id="edit-todays-tasks_modal"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.appendChild(form);
	}
}
