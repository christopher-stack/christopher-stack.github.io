var time;

const demoString = 
`Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. 
Vestibulum tortor quam, feugiat vitae, ultricies eget, tempor sit amet, ante. 
Donec eu libero sit amet quam egestas semper. Aenean ultricies mi vitae est. 
Mauris placerat eleifend leo. Quisque sit amet est et sapien ullamcorper pharetra. 
Vestibulum erat wisi, condimentum sed, commodo vitae, ornare sit amet, wisi. 
Aenean fermentum, elit eget tincidunt condimentum, eros ipsum rutrum orci, sagittis tempus lacus enim ac dui. 
Donec non enim in turpis pulvinar facilisis. Ut felis. 
Praesent dapibus, neque id cursus faucibus, tortor neque egestas augue, eu vulputate magna eros eu erat. 
Aliquam erat volutpat. Nam dui mi, tincidunt quis, accumsan porttitor, facilisis luctus, metus`;
function calcTime() {
	time = setTimeout(showPage, 2000);
}
function showPage() {
	document.getElementById("loader").style.display = "none";
	document.getElementById("webDiv").style.display = "block";
}
function addEdu(eduHistCount) {
	eduHistCount++;
	// heading and edu count
	let heading = document.createElement('span');
	let headingTxt = document.createElement('p');
	headingTxt.classList.add('form-subheader2');
	headingTxt.textContent = `[ EDU #${eduHistCount}]`;
	heading.appendChild(headingTxt);
	// create 5 input-group divs
    let inputGroupA = document.createElement('div');
	inputGroupA.classList.add('input-group', 'mb-4');
    let inputGroupB = document.createElement('div');
    inputGroupB.classList.add('input-group', 'mb-4');
    let inputGroupC = document.createElement('div');
	inputGroupC.classList.add('input-group', 'mb-4');
	let inputGroupD = document.createElement('div');
	inputGroupD.classList.add('form-group', 'mb-4');
	let inputGroupE = document.createElement('div');
    inputGroupE.classList.add('input-group', 'mb-4');
	// area of study & degree(inputGroupA; form A & B)
	let formGroupA = document.createElement('div');
	formGroupA.classList.add('form-group', 'mr-3', 'col-md-5');
	let studyLabel = document.createElement("label");
	studyLabel.for = `eduHistAreaOfStudyEntry${eduHistCount}`;
	studyLabel.textContent = "Area Of Study";
	let studyInput = document.createElement("input");
	studyInput.type = "text";
    studyInput.className = "form-control";
    studyInput.name = `eduHistAreaOfStudyEntry${eduHistCount}`;
	studyInput.required = true;
	studyInput.setAttribute('data-error', 'This field is required.');
	formGroupA.appendChild(studyLabel);
	formGroupA.appendChild(studyInput);
	let formGroupB = document.createElement('div');
	formGroupB.classList.add('form-group', 'mr-3', 'col-md-5');
	let degreeLabel = document.createElement("label");
	degreeLabel.for = `eduHistDegreeEntry${eduHistCount}`;
	degreeLabel.textContent = "Degree";
	let degreeMenuDiv = document.createElement('div');
	degreeMenuDiv.classList.add('input-group', 'mb-2');
	let degreeSelect = document.createElement('select');
	degreeSelect.classList.add('form-select');
	degreeSelect.name = `eduHistDegreeEntry${eduHistCount}`;
	degreeSelect.required = true;
	degreeSelect.setAttribute('data-error', 'This field is required.');
	let opt0 = document.createElement('option');
	opt0.selected = opt0.disabled = true;
	opt0.value = "";
	opt0.textContent = "Please select degree";
	let opt1 = document.createElement('option');
	opt1.value = "High School";
	opt1.textContent = "High School";
	let opt2 = document.createElement('option');
	opt2.value = "Bachelors";
	opt2.textContent = "Bachelors";
	let opt3 = document.createElement('option');
	opt3.value = "Associates";
	opt3.textContent = "Associates";
	let opt4 = document.createElement('option');
	opt4.value = "Masters";
	opt4.textContent = "Masters";
	let opt5 = document.createElement('option');
	opt5.value = "Doctorate";
	opt5.textContent = "Doctorate";
	degreeSelect.appendChild(opt0);
	degreeSelect.appendChild(opt1);
	degreeSelect.appendChild(opt2);
	degreeSelect.appendChild(opt3);
	degreeSelect.appendChild(opt4);
	degreeSelect.appendChild(opt5);
	degreeMenuDiv.appendChild(degreeSelect);
	formGroupB.appendChild(degreeLabel);
	formGroupB.appendChild(degreeMenuDiv);
	inputGroupA.appendChild(formGroupA);
	inputGroupA.appendChild(formGroupB);

	// start date & end date(inputGroupB; form C & D)
	let formGroupC = document.createElement('div');
	formGroupC.classList.add('form-group', 'mr-3', 'col-md-5');
	let startLabel = document.createElement('label');
	startLabel.for = `eduHistStartEntry${eduHistCount}`;
	startLabel.textContent = "Start Date";
	let startDiv = document.createElement('div');
	startDiv.classList.add('input-group', 'date');
	startDiv.setAttribute('data-date-format', 'dd.mm.yyyy');
	let startInput = document.createElement("input");
	startInput.type = "date";
    startInput.className = "form-control";
    startInput.name = `eduHistStartEntry${eduHistCount}`;
	startInput.required = true;
	startInput.placeholder="dd.mm.yyyy";
	startInput.setAttribute('data-error', 'This field is required.');
	startDiv.appendChild(startInput);
	formGroupC.appendChild(startLabel);
	formGroupC.appendChild(startDiv);
	let formGroupD = document.createElement('div');
	formGroupD.classList.add('form-group', 'mr-3', 'col-md-5');
	let endLabel = document.createElement('label');
	endLabel.for = `eduHistEndEntry${eduHistCount}`;
	endLabel.textContent = "End Date";
	let endDiv = document.createElement('div');
	endDiv.classList.add('input-group', 'date');
	endDiv.setAttribute('data-date-format', 'dd.mm.yyyy');
	let endInput = document.createElement("input");
	endInput.type = "date";
    endInput.className = "form-control";
    endInput.name = `eduHistEndEntry${eduHistCount}`;
	endInput.required = true;
	endInput.placeholder="dd.mm.yyyy";
	endInput.setAttribute('data-error', 'This field is required.');
	endDiv.appendChild(endInput);
	formGroupD.appendChild(endLabel);
	formGroupD.appendChild(endDiv);
	inputGroupB.appendChild(formGroupC);
	inputGroupB.appendChild(formGroupD);

	// type and gpa (inputGroup C; form E & F)
	let formGroupE = document.createElement('div');
	formGroupE.classList.add('form-group', 'mr-3', 'col-md-5');
	let typeLabel = document.createElement("label");
	typeLabel.for = `eduHistFacilityTypeEntry${eduHistCount}`;
	typeLabel.textContent = "Institution Type";
	let typeMenuDiv = document.createElement('div');
	typeMenuDiv.classList.add('input-group', 'mb-2');
	let typeSelect = document.createElement('select');
	typeSelect.classList.add('form-select');
	typeSelect.name = `eduHistFacilityTypeEntry${eduHistCount}`;
	typeSelect.required = true;
	typeSelect.setAttribute('data-error', 'This field is required.');
	let opt6 = document.createElement('option');
	opt6.selected = opt6.disabled = true;
	opt6.value = "";
	opt6.textContent = "Please select institution type";
	let opt7 = document.createElement('option');
	opt7.value = "University";
	opt7.textContent = "University";
	let opt8 = document.createElement('option');
	opt8.value = "College";
	opt8.textContent = "College";
	let opt9 = document.createElement('option');
	opt9.value = "Technical School";
	opt9.textContent = "Technical School";
	let opt10 = document.createElement('option');
	opt10.value = "High School";
	opt10.textContent = "High School";
	let opt11 = document.createElement('option');
	opt11.value = "Grade School";
	opt11.textContent = "Grade School";
	typeSelect.appendChild(opt6);
	typeSelect.appendChild(opt7);
	typeSelect.appendChild(opt8);
	typeSelect.appendChild(opt9);
	typeSelect.appendChild(opt10);
	typeSelect.appendChild(opt11);
	typeMenuDiv.appendChild(typeSelect);
	formGroupE.appendChild(typeLabel);
	formGroupE.appendChild(typeMenuDiv);
	let formGroupF = document.createElement('div');
	formGroupF.classList.add('form-group', 'mr-3', 'col-md-5');
	let gpaLabel = document.createElement("label");
	gpaLabel.for = `eduHistGpaEntry${eduHistCount}`;
	gpaLabel.textContent = "GPA";
	let gpaInput = document.createElement("input");
	gpaInput.type = "number";
    gpaInput.className = "form-control";
    gpaInput.name = `eduHistGpaEntry${eduHistCount}`;
	gpaInput.required = true;
	gpaInput.setAttribute('data-error', 'This field is required.');
	gpaInput.min = "0";
	gpaInput.max = "4";
	gpaInput.step = ".001";
	formGroupF.appendChild(gpaLabel);
	formGroupF.appendChild(gpaInput);
    inputGroupC.appendChild(formGroupE);
	inputGroupC.appendChild(formGroupF);

	// institution name (inputGroup D)
	let nameLabel = document.createElement("label");
	nameLabel.for = `eduHistFacilityNameEntry${eduHistCount}`;
	nameLabel.textContent = "Institution Name";
	let nameInput = document.createElement("input");
	nameInput.type = "text";
    nameInput.className = "form-control";
    nameInput.name = `eduHistFacilityNameEntry${eduHistCount}`;
	nameInput.required = true;
	nameInput.setAttribute('data-error', 'This field is required.');
	inputGroupD.appendChild(nameLabel);
	inputGroupD.appendChild(nameInput);

	// city, state, zip (inputGroup E; form G, H, I)
	let formGroupG = document.createElement('div');
	formGroupG.classList.add('form-group', 'mr-3', 'col-md-3');
	let cityLabel = document.createElement("label");
	cityLabel.for = `eduHistFacilityCityEntry${eduHistCount}`;
	cityLabel.textContent = "City";
	let cityInput = document.createElement("input");
	cityInput.type = "text";
    cityInput.className = "form-control";
    cityInput.name = `eduHistFacilityCityEntry${eduHistCount}`;
	cityInput.required = true;
	cityInput.setAttribute('data-error', 'This field is required.');
	formGroupG.appendChild(cityLabel);
	formGroupG.appendChild(cityInput);
	let formGroupH = document.createElement('div');
	formGroupH.classList.add('form-group', 'mr-3', 'col-md-3');
	let stateLabel = document.createElement("label");
	stateLabel.for = `eduHistFacilityStateEntry${eduHistCount}`;
	stateLabel.textContent = "State";
	let stateInput = document.createElement("input");
	stateInput.type = "text";
    stateInput.className = "form-control";
    stateInput.name = `eduHistFacilityStateEntry${eduHistCount}`;
	stateInput.required = true;
	stateInput.setAttribute('data-error', 'This field is required.');
	formGroupH.appendChild(stateLabel);
	formGroupH.appendChild(stateInput);
	let formGroupI = document.createElement('div');
	formGroupI.classList.add('form-group', 'mr-3', 'col-md-3');
	let postalLabel = document.createElement("label");
	postalLabel.for = `eduHistFacilityPostalEntry${eduHistCount}`;
	postalLabel.textContent = "Zip";
	let postalInput = document.createElement("input");
	postalInput.type = "text";
    postalInput.className = "form-control";
    postalInput.name = `eduHistFacilityPostalEntry${eduHistCount}`;
	postalInput.required = true;
	postalInput.setAttribute('data-error', 'This field is required.');
	formGroupI.appendChild(postalLabel);
	formGroupI.appendChild(postalInput);
	inputGroupE.appendChild(formGroupG);
	inputGroupE.appendChild(formGroupH);
	inputGroupE.appendChild(formGroupI);

	// insertBefore #addEduBtn
	let profileForm = document.getElementById('profileForm');
	let addBtn = document.getElementById('addEduBtn');
	profileForm.insertBefore(heading, addBtn);
	profileForm.insertBefore(inputGroupA, addBtn);
	profileForm.insertBefore(inputGroupB, addBtn);
	profileForm.insertBefore(inputGroupC, addBtn);
	profileForm.insertBefore(inputGroupD, addBtn);
	profileForm.insertBefore(inputGroupE, addBtn);
}

function showMore(id) {
	let targetEl = document.getElementById(`details${id}`);
	if (targetEl.classList.contains("hidden")) {
		targetEl.classList.remove("hidden");
	}
	let moreBtn = document.getElementById(`more${id}`);
	moreBtn.classList.add("hidden");
	let lessBtn = document.getElementById(`less${id}`);
	if (lessBtn.classList.contains("hidden")) {
		lessBtn.classList.remove("hidden");
	}
}

function showLess(id) {
	let targetEl = document.getElementById(`details${id}`);
	targetEl.classList.add("hidden");
	let moreBtn = document.getElementById(`more${id}`);
	if (moreBtn.classList.contains("hidden")) {
		moreBtn.classList.remove("hidden");
	}
	let lessBtn = document.getElementById(`less${id}`);
	lessBtn.classList.add("hidden");
}

function suggestSearch(str) {
	if (str.length == 0) {
		document.getElementById("suggestions").innerHTML = "";
		document.getElementById("suggestions").style.border = "0px";
		return;
	}
	let xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			document.getElementById("suggestions").innerHTML = this.responseText;
			document.getElementById("suggestions").style.border = "1px solid #A5ACB2";
		}
	}
	xmlhttp.open("GET", "../Resources/php/server/SuggestSearch.php?q="+str, true);
	xmlhttp.send();
}

function search(str) {
	if (str.length == 0) return;
	fetchSearchData(str);
}

function fetchSearchData(str) {
	fetch('../Resources/php/server/SuggestSearch.php?q=' + str)
	.then(res => res.text())
	.then(data => listSuggestions(data))
	.catch(err => console.log('Error: ' + err));
}

function listSuggestions(data) {
	const suggestionsList = document.getElementById("suggestionsList");
	suggestionsList.innerHTML = "";

	const searchField = document.getElementById('searchField');
	const form = document.getElementById('searchForm');
	suggestionsArray = JSON.parse(data)["positions"];
	
	for (i = 0; i < suggestionsArray.length; i++) {
		const p = document.createElement('p');
		p.innerHTML = suggestionsArray[i];
		p.onclick = function searchItem() {
			suggestionsList.classList.add('hidden');
			searchField.value = this.innerHTML;
			form.submit();
		};
		suggestionsList.appendChild(p);
	}
}

function sortByCol(table, col, id, asc=true) {

	let head = document.getElementById(id);
	if (asc) {
		head.setAttribute("onclick", "sortByCol(document.querySelector('table'), 1, this.id, false)");
	}
	else {
		head.setAttribute("onclick", "sortByCol(document.querySelector('table'), 1, this.id)");
	}
	

	const dirMod = asc ? 1 : -1;
	const tableBody = table.tBodies[0];
	const oddrows = Array.from(tableBody.querySelectorAll("tr:nth-child(odd)"));
	const evenrows = Array.from(tableBody.querySelectorAll("tr:nth-child(even)"));
	
	// console.log(evenrows);
	let allrows = [];

	for (i = 0; i < evenrows.length; i++) {
		allrows[i] = [oddrows[i], evenrows[i]];
	}

	const sortedRows = allrows.sort((a, b) => {
		const aColText = a[0].querySelector(`td:nth-child(${col})`).textContent.trim();
		const bColText = b[0].querySelector(`td:nth-child(${col})`).textContent.trim();

		return aColText > bColText ? (1 * dirMod) : (-1 * dirMod);
	})

	while (tableBody.firstChild) {
		tableBody.removeChild(tableBody.firstChild);
	}

	for (i = 0; i < evenrows.length; i++) {
		tableBody.appendChild(allrows[i][0]);
		tableBody.appendChild(allrows[i][1]);
	}
}