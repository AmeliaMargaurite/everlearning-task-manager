import { taskRequestURL } from "../js/helpers.js";

// Opens task dialog when task tile clicked
export function editTask(task_id) {
	const dialog = document.createElement("edit-task-dialog");
	dialog.setAttribute("task_id", task_id);
	document.body.appendChild(dialog);
}

// Opens note dialog when note is clicked
export function editNote(note_id) {
	const dialog = document.createElement("edit-note-dialog");
	dialog.setAttribute("note_id", note_id);
	document.body.appendChild(dialog);
}

// Drag and drop functions for Desktop
export function handleDragStart(e, task_id) {
	e.dataTransfer.setData("task_id", task_id);
}

export async function handleDrop(e, status) {
	e.preventDefault();
	const task_id = e.dataTransfer.getData("task_id");
	if (task_id) {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({
				request: "update_task_status",
				task_id,
				newStatus: status,
				project_id: "<?php echo $project_id ?>",
			}),
		});
		location.reload();
	}
}

export function handleOnDragOver(e) {
	e.preventDefault();
}

// Mobile touch functions
const taskTiles = document.querySelectorAll(".task__tile");
for (const tile of taskTiles) {
	let timeout, longtouch;

	tile.addEventListener("touchstart", function () {
		timeout = setTimeout(function () {
			longtouch = true;
		}, 400);
	});
	tile.addEventListener("touchend", function (e) {
		if (longtouch) {
			e.preventDefault();
			tile.classList.add("mobile-selected");
			const tileContextMenu = document.createElement("tile-context-menu");
			tileContextMenu.setAttribute("task_id", tile.getAttribute("task_id"));
			const currentStatus = tile.parentElement.id;
			tileContextMenu.setAttribute("current_status", currentStatus);
			tile.parentElement.append(tileContextMenu);
		}
		longtouch = false;
		clearTimeout(timeout);
	});
}

// Adding colours to the legend markers
const legendMarkers = document.querySelectorAll(".category_color");
for (const marker of legendMarkers) {
	const color = marker.getAttribute("color");
	marker.style.background = color;
}
