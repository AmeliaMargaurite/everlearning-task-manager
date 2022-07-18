export class RichTextEditor extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const nameToSave = this.getAttribute("name-to-save");
		const savedData = this.getAttribute("data");
		this.id = "editor";
		this.innerHTML = `
		<div class="toolbar" id="toolbar">
			<span class="editor-btn" data-action="bold" data-tag-name="strong" title="Bold">
				<div class="icon bold"></div>
			</span>
			<span class="editor-btn" data-action="italic" data-tag-name="em" title="Italic">
				<div class="icon italic"></div>
			</span>
			<span class="editor-btn" data-action="underline" data-tag-name="u" title="Underline">
				<div class="icon underline"></div>
			</span>
			<span class="editor-btn" data-action="strikeThrough" data-tag-name="strike" title="Strike through">
				<div class="icon strike-through"></div>
			</span>


			<span class="editor-btn has-submenu" id="align-button">
				<div class="icon align-left" ></div>
				<div class="submenu" id="align-buttons__wrapper">
					<span class="editor-btn" data-action="justifyLeft" data-style="textAlign:left" title="Justify left">
						<div class="icon align-left"></div>
					</span>
					<span class="editor-btn" data-action="justifyCenter" data-style="textAlign:center" title="Justify center">
						<div class="icon align-center"></div>
					</span>
					<span class="editor-btn" data-action="justifyRight" data-style="textAlign:right" title="Justify right">
						<div class="icon align-right"></div>
					</span>
					<span class="editor-btn" data-action="formatBlock" data-style="textAlign:justify" title="Justify block">
						<div class="icon align-justify"></div>
					</span>
				</div>
			</span>

			<span class="editor-btn" data-action="insertOrderedList" data-tag-name="ol" title="Insert ordered list">
				<div class="icon ol"></div>
			</span>
			<span class="editor-btn" data-action="insertUnorderedList" data-tag-name="ul" title="Insert unordered list">
				<div class="icon ul"></div>			
			</span>
			<span class="editor-btn" data-action="outdent" data-required-tag="li" title="Outdent">				
				<div class="icon outdent"></div>			
			</span>
			<span class="editor-btn" data-action="indent" title="Indent">				
				<div class="icon indent"></div>			
			</span>

			<span class="editor-btn" data-action="insertHorizontalRule" title="Insert horizontal rule">
				<div class="icon hl"></div>
			</span>
			<span class="editor-btn" data-action="undo" title="Undo">
				<div class="icon undo"></div>
			</span>
			<span class="editor-btn" data-action="removeFormat" title="Remove format">
				<div class="icon eraser"></div>
			</span>
			<span class="editor-btn" data-action="createLink" title="Insert Link">				
				<div class="icon link"></div>			
			</span>
			<span class="editor-btn" data-action="unlink" data-tag-name="a" title="Unlink">				
				<div class="icon unlink"></div>			
			</span>
			<span class="editor-btn" data-action="toggle-view" title="Show HTML code">
				<div class="icon code"></div>
			</span>
		</div>

		<div class="content-area" id="content-area">
			<div class="visual-view" id="visual-view" contentEditable="true" >${savedData}</div>
			<textarea class="html-view" id="html-view" name="${nameToSave}"></textarea>
		</div>

		`;

		const editor = document.getElementById("editor");
		const toolbar = document.getElementById("toolbar");
		const buttons = document.querySelectorAll(".editor-btn:not(.has-submenu)");
		const contentArea = document.getElementById("content-area");
		const visualView = document.getElementById("visual-view");
		const htmlView = document.getElementById("html-view");

		const alignBtn = document.getElementById("align-button");
		const alignBtnsWrapper = document.getElementById("align-buttons__wrapper");

		alignBtn.onclick = () => alignBtnsWrapper.classList.toggle("open");

		let selectedText, selectedRange;
		const captureSelection = () => {
			if (window.getSelection) {
				const sel = window.getSelection();
				selectedText = window.getSelection().toString();

				if (sel.getRangeAt && sel.rangeCount) {
					selectedRange = window.getSelection().getRangeAt(0).cloneRange();
					return;
				}
			}

			selectedRange = null;
			selectedText = "";
		};

		visualView.addEventListener("mouseup", captureSelection);

		// const selectionChange = (e) => {
		// 	for (const button of buttons) {
		// 		if (button.dataset.action === "toggle-view") continue;
		// 		button.classList.remove("active");
		// 	}

		// 	if (!childOf(window.getSelection().anchorNode.parentNode, editor))
		// 		return false;

		// 	parentTagActive(window.getSelection().anchorNode.parentNode);
		// };

		// document.addEventListener("selectionchange", selectionChange);

		// // Checks if node is child of supplied parent node
		// const childOf = (child, parent) => parent.contains(child);

		// //
		// const parentTagActive = (el) => {
		// 	if (!el || !el.classList || el.classList.contains("visual-view"))
		// 		return false;
		// 	let toolbarButton;

		// 	// active by tag names
		// 	const tagName = el.tagName.toLowerCase();
		// 	toolbarButton = document.querySelectorAll(
		// 		`.toolbar .editor-btn[data-tag-name="${tagName}"]`
		// 	)[0];
		// 	if (toolbarButton) {
		// 		toolbarButton.classList.add("active");
		// 	}

		// 	// active by text-align
		// 	const textAlign = el.style.textAlign;
		// 	toolbarButton = document.querySelectorAll(
		// 		`.toolbar .editor-btn[data-style="textAlign:${textAlign}"]`
		// 	)[0];
		// 	if (toolbarButton) {
		// 		toolbarButton.classList.add("active");
		// 	}

		// 	return parentTagActive(el.parentNode);
		// };

		// add toolbar button actions
		for (const button of buttons) {
			button.addEventListener("click", function (e) {
				const action = this.dataset.action;
				console.log(action);
				switch (action) {
					case "toggle-view":
						execCodeAction(this, editor);
						break;
					case "createLink":
						// execLinkAction();
						break;
					default:
						const selection = selectedText;
						const range = selectedRange;
						applyFormatting(this, selection, range);
				}
			});
		}

		const applyFormatting = (button, selection, range) => {
			if (!selection || !range) return false;
			console.log(button);
			const tagName = button.dataset.tagName;
			const style = button.dataset.style;
			// formatting buttons eg bold, italic, underline etc
			if (tagName) {
				const el = document.createElement(tagName);

				el.innerHTML = selection;
				const container = range.startContainer;

				const parentHasSameElWrapper = (el, wrapper) => {
					const parentEl = el.parentElement;

					if (parentEl.tagName.toLowerCase() === wrapper.toLowerCase()) {
						if (parentEl.children.length > 0) {
							// don't currently have a good method here @TODO
							// so not currently removing a matched wrapper if it's
							// not the nearest wrapper to the selected contents
						} else {
							const contents = parentEl.innerText;
							parentEl.replaceWith(contents);
						}
						return true;
					} else if (parentEl === visualView) {
						return false;
					} else {
						return parentHasSameElWrapper(el.parentElement, wrapper);
					}
				};

				// removes selected formatting if it exists already
				if (parentHasSameElWrapper(container, tagName)) return;

				// find nearest wrapping p
				// check if p has text-align set, else set it
				// if no nearest wrapping p, wrap content in p and add text align

				range.deleteContents();
				range.insertNode(el);
			}

			if (style) {
				const p = document.createElement("p");
				if (parentHasSameElWrapper(container, "p")) return;
			}
			selectedRange = null;
			selectedText = "";
		};

		function execCodeAction(button, editor) {
			if (button.classList.contains("active")) {
				visualView.innerHTML = htmlView.value;
				htmlView.style.display = "none";
				visualView.style.display = "block";
				button.classList.remove("active");
			} else {
				htmlView.innerText = visualView.innerHTML;
				visualView.style.display = "none";
				htmlView.style.display = "block";
				button.classList.add("active");
			}
		}

		// function execLinkAction() {
		// 	const linkPopup = document.createElement("create-link-popup");
		// 	document.body.appendChild(linkPopup);
		// 	const popup = document.getElementsByTagName("create-link-popup")[0];

		// 	const selection = saveSelection();
		// 	const saveBtn = document.getElementById("create-link-save");

		// 	saveBtn.onclick = (e) => {
		// 		e.preventDefault();
		// 		const newTabCheckbox = document.getElementById("new-tab");
		// 		const linkInput = document.getElementById("linkValue");
		// 		const linkValue = popup.value;

		// 		restoreSelection(selection);

		// 		if (window.getSelection().toString()) {
		// 			const a = document.createElement("a");
		// 			a.href = linkValue;

		// 			if (newTab) a.target = "_blank";
		// 			window.getSelection().getRangeAt(0).surroundContents(a);
		// 		}

		// 		document.body.removeChild(linkPopup);
		// 	};
		// }

		const saveSelection = () => {
			if (window.getSelection) {
				const sel = window.getSelection();
				console.log({ sel });
				if (sel.getRangeAt && sel.rangeCount) {
					let ranges = [];
					for (let i = 0, n = sel.rangeCount; i < n; i++) {
						ranges.push(sel.getRangeAt(i));
					}
					return ranges;
				}
			} else if (document.selection && document.selection.createRange) {
				return document.selection.createRange();
			}
			return null;
		};

		// const restoreSelection = (savedSel) => {
		// 	if (savedSel) {
		// 		if (window.getSelection) {
		// 			const sel = window.getSelection();
		// 			sel.removeAllRanges();

		// 			for (let i = 0, n = savedSel.length; i < n; i++) {
		// 				sel.addRange(savedSel[i]);
		// 			}
		// 		} else if (document.selection && savedSel.select) {
		// 			savedSel.select();
		// 		}
		// 	}
		// };

		// //
		// const pasteEvent = (e) => {
		// 	e.preventDefault();
		// 	const text = (e.orignalEvent || e).clipboardData.getData("text/plain");
		// 	document.execCommand("insertHTML", false, text);
		// };

		visualView.onfocus = function () {
			if (window.getSelection && document.createRange) {
				const range = document.createRange();
				range.selectNodeContents(this);
				range.collapse(false);

				const sel = window.getSelection();
				sel.removeAllRanges();
				sel.addRange(range);
			}
		};

		const addParagraphTag = (e) => {
			console.log(e);
			if (e.keyCode === 13) {
				if (window.getSelection().anchorNode.parentNode.tagName === "LI")
					return;
				const sel = window.getSelection();
				if (sel.getRangeAt && sel.rangeCount) {
					const range = window.getSelection().getRangeAt(0);
					const p = document.createElement("p");
					p.innerHTML = "<br>";
					range.insertNode(p);
					return;
				}
			}
		};

		// visualView.addEventListener("paste", pasteEvent);
		visualView.addEventListener("keypress", addParagraphTag);

		const saveBtn = document.querySelector('button[form="modal-form"]');
		saveBtn.onclick = () => {
			if (visualView.style.display !== "none") {
				htmlView.innerText = visualView.innerHTML;
			}

			return true;
		};
	}
}

class CreateLinkPopup extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const closeCreateLinkPopup = () => {
			this.parentNode.removeChild(this);
			document.removeEventListener("click", closeCreateLinkPopup);
		};

		this.innerHTML = `
		 <div class="overlay" id="create-link-popup-overlay">
			<div class="link-popup__wrapper" onclick="event.stopPropagation()">
				<div class="heading--wrapper">
					<h3>Insert link</h3><button class="icon-only" id="close-link-popup">
					<div class="icon close"></div></button>
				</div>
				<input type="text" id="linkValue" placeholder="www.everlearning.com.au"/>
				<label for="new-tab"><input type="checkbox" name="new-tab" id="new-tab"/>Open in new tab?</label>
				<button class="special" id="create-link-save">Save</button> 
			</div>
		 </div>
		`;

		const overlay = document.getElementById("create-link-popup-overlay");
		overlay.onclick = closeCreateLinkPopup;

		const closeBtn = document.getElementById("close-link-popup");
		closeBtn.onclick = closeCreateLinkPopup;
	}
}

customElements.define("create-link-popup", CreateLinkPopup);
