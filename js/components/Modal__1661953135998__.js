import { closeModal } from "../helpers__1661953135998__.js";

export class Modal extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const modalsId = this.getAttribute("id");

		this.innerHTML = `
			<modal-overlay modals_id='${modalsId}'>
        <div class="wrapper" onclick="event.stopPropagation()">
          <div id="modal--contents" class="modal--contents" ></div>
					<span class="modal-buttons">
						<button class="btn" type="cancel" id="cancel-btn">Cancel</button>
						<button class="btn special" type="submit" form="modal-form" >Save</button>
					</span>
        </div>
			</modal-overlay>
    `;

		const cancelBtn = document.getElementById("cancel-btn");
		cancelBtn.onclick = () => closeModal(modalsId);
	}
}
