const fs = require("fs");
const path = require("path");

try {
	const newFileAddition = "__" + Date.now() + "__";

	const getAllFiles = (dirPath, arrayOfFiles) => {
		const files = fs.readdirSync(dirPath);

		arrayOfFiles = arrayOfFiles || [];

		files.forEach((file, index) => {
			const currentFilePath = dirPath + "/" + file;
			const regexp = /__\d+__/g;
			const fileName = file.split(regexp);

			if (file.startsWith(".") || file.startsWith("vendor")) return;

			if (fs.statSync(currentFilePath).isDirectory()) {
				arrayOfFiles = getAllFiles(currentFilePath, arrayOfFiles);
				return;
			}

			if (file.endsWith(".js") && file !== "build.js") {
				console.log(file);
				if (fileName.length === 2) {
					// Files which have already been build in this wayy (contain __000___);
					fs.rename(
						currentFilePath,
						dirPath + "/" + fileName[0] + newFileAddition + fileName[1],
						(e) => {}
					);
				} else {
					// New files/unaltered files
					const name = file.split(".js");
					fs.rename(
						currentFilePath,
						dirPath + "/" + name[0] + newFileAddition + ".js",
						(e) => {}
					);
				}
			}

			// Handle CSS files, main.css or previously edited main__000___.css file.
			if (file.endsWith(".css")) {
				if (files.includes((f) => f.includes("main.css"))) {
					if (fileName.length > 1) {
						// delete this old file
						fs.unlinkSync(currentFilePath);
					} else if (fileName.length === 1) {
						fs.rename(
							currentFilePath,
							dirPath + "/main" + newFileAddition + ".css",
							(e) => {}
						);
					}
				} else {
					fs.rename(
						currentFilePath,
						dirPath + "/main" + newFileAddition + ".css",
						(e) => {}
					);
				}
			}

			if (file.endsWith(".js") && file !== "build.js") {
				// const fileName = file.split(regexp);
				const data = fs.readFile(currentFilePath, "utf-8", (e, readData) => {
					if (!readData) return;
					const replaced = readData.replace(regexp, newFileAddition);
					if (replaced !== readData) {
						fs.writeFile(
							dirPath + "/" + fileName[0] + newFileAddition + fileName[1],
							replaced,
							{ encoding: "utf-8" },
							(e) => {}
						);
					}
				});
			}

			if (file.endsWith(".php")) {
				const data = fs.readFile(currentFilePath, "utf-8", (e, data) => {
					const replaced = data.replace(regexp, newFileAddition);
					if (replaced !== data) {
						fs.writeFile(
							currentFilePath,
							replaced,
							{ encoding: "utf-8" },
							(e) => {}
						);
					}
				});
			}
		});
		return arrayOfFiles;
	};

	const result = getAllFiles(__dirname);
	console.log(result);
} catch (e) {
	console.log("e: " + e);
}
