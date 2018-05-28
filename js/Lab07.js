(function () {
  let tablesArray = [];

  class Table {
    constructor(title, columnNumber, attributes) {
      this.title = title;
      this.columnNumber = columnNumber;
      this.attributes = attributes;
      this.cellsData = [];
      this.tableInDOM = document.createElement("table");
      this.tHead = this.tableInDOM.createTHead();
      this.tBody = this.tableInDOM.createTBody();
      let tr = document.createElement("tr");
      let newCell;
      for (let i = 0; i < this.columnNumber; i++) {
        newCell = tr.insertCell(i);
        newCell.innerText = this.attributes[i];
      }
      this.tHead.appendChild(tr);
    }

    appendRow(rowData) {
      this.cellsData.push(rowData);
      let newCell;
      let newRow = this.tBody.insertRow(this.tableInDOM.length);
      for (let i = 0; i < this.columnNumber; i++) {
        newCell = newRow.insertCell(i);
        newCell.innerText = rowData[i];
      }
      this.refreshDOM();
    }

    deleteRow(rowData) {
      let check = true;
      for (let l = 0; l < rowData.length; l++) {
        if (rowData[l] !== "" && rowData[l] !== this.cellsData[0][l])
          check = false;
      }
      if (check === true) {
        this.cellsData.splice(0, 1);
        this.tableInDOM.deleteRow(1);
      }
      for (let k = 0; k < this.cellsData.length * 2; k++)
        LoopForRows:
            for (let i = 0; i < this.cellsData.length; i++) {
              let j = 0;
              for (j = 0; j < rowData.length; j++) {
                // if (rowData[j] === "") continue;
                // if (rowData[j] !== this.cellsData[i][j]) break;
                if (rowData[j] !== "" && rowData[j] !== this.cellsData[i][j])
                  continue LoopForRows;
              }
              if (j === rowData.length) {
                this.cellsData.splice(i, 1);
                this.tableInDOM.deleteRow(i + 1);
              }
            }
      check = true;
      for (let l = 0; l < rowData.length; l++) {
        if (rowData[l] !== "" && rowData[l] !== this.cellsData[0][l])
          check = false;
      }
      if (check === true) {
        this.cellsData.splice(0, 1);
        this.tableInDOM.deleteRow(1);
      }
      this.refreshDOM();
    }

    refreshDOM() {
      let containerDiv = document.getElementById("table_placeholder");
      containerDiv.innerHTML = "";
      containerDiv.appendChild(this.tableInDOM);
    }
  }

  function createTableStepOne() {
    document.form.tableNameInput.style.display = "inline-block";
    document.form.columnNumberInput.style.display = "inline-block";
    document.form.columnNumberInput.onchange = createTableStepTwo;
  }

  function createTableStepTwo() {
    let tableName = document.form.tableNameInput.value;
    let columnNumber = document.form.columnNumberInput.value;
    if (tableName !== "" && columnNumber > 0) {
      document.getElementById("inputsForHeader").innerHTML = "";
      document.getElementsByTagName("button")[0].style.display = "inline-block";
      for (let i = 0; i < columnNumber; i++) {
        let input = document.createElement("input");
        input.placeholder = "Attribute";
        input.type = "text";
        input.className = "tableAttributes";
        document.getElementById("inputsForHeader").appendChild(input);
      }
      document.getElementsByTagName("button")[0].onclick = createTable;
    }
  }

  function createTable() {
    let tableName = document.form.tableNameInput.value;
    let columnNumber = document.form.columnNumberInput.value;
    let attributes = [];
    for (let i = 0; i < columnNumber; i++) {
      attributes[i] = document.form.getElementsByClassName("tableAttributes")[i].value;
    }
    let tableIndex = tablesArray.length;
    tablesArray.push(new Table(tableName, columnNumber, attributes));
    document.getElementById("table_placeholder").innerHTML = "";
    tablesArray[tableIndex].refreshDOM();
    let optionInSelect = document.createElement("option");
    optionInSelect.innerText = tableName;
    optionInSelect.selected = true;
    document.form.select2.appendChild(optionInSelect);
  }

  function inputForAddDeleteRow(currentTable) {
    if (document.form.select2.selectedIndex === 0) return;
    document.getElementsByTagName("button")[0].style.display = "inline-block";
    let columnNumber = currentTable.columnNumber;
    for (let i = 0; i < columnNumber; i++) {
      let input = document.createElement("input");
      input.placeholder = currentTable.attributes[i];
      input.type = "text";
      document.getElementById("inputsForHeader").appendChild(input);
    }
    document.getElementsByTagName("button")[0].style.display = "inline-block";
  }

  function addRow() {
    let currentTable = tablesArray[document.form.select2.selectedIndex - 1];
    inputForAddDeleteRow(currentTable);
    let inputsArray = document.getElementById("inputsForHeader").children;
    document.getElementsByTagName("button")[0].onclick = function () {
      let rowData = [];
      for (let i = 0; i < currentTable.columnNumber; i++) {
        rowData.push(inputsArray[i].value);
      }
      currentTable.appendRow(rowData);
    }
  }

  function deleteRow() {
    let currentTable = tablesArray[document.form.select2.selectedIndex - 1];
    inputForAddDeleteRow(currentTable);
    let inputsArray = document.getElementById("inputsForHeader").children;
    document.getElementsByTagName("button")[0].onclick = function () {
      let rowData = [];
      for (let i = 0; i < currentTable.columnNumber; i++)
        rowData.push(inputsArray[i].value);
      currentTable.deleteRow(rowData);
    }
  }

  function deleteTable() {
    document.getElementsByTagName("p")[0].style.display = "block";
    document.getElementsByTagName("button")[0].onclick = function () {
      let currentTable = tablesArray[document.form.select2.selectedIndex - 1];
      tablesArray.splice(currentTable, 1);
      let selectTwo = document.form.select2;
      selectTwo.removeChild(selectTwo.options[selectTwo.selectedIndex]);
      let containerDiv = document.getElementById("table_placeholder");
      containerDiv.innerHTML = "";
    }
  }

  document.form.select1.onchange = function () {
    let index = this.selectedIndex;
    document.getElementsByTagName("p")[0].style.display = "none";
    document.getElementById("inputsForHeader").innerHTML = "";
    document.form.tableNameInput.style.display = "none";
    document.form.columnNumberInput.style.display = "none";
    switch (index) {
      case 1:
        createTableStepOne();
        break;
      case 2:
        addRow();
        break;
      case 3:
        deleteRow();
        break;
      case 4:
        deleteTable();
        break;
    }
  };
  document.form.select2.onchange = function () {
    if (this.selectedIndex > 0) {
      tablesArray[document.form.select2.selectedIndex - 1].refreshDOM();
    }
  }
})();