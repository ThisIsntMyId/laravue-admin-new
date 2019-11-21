// ? Reference for csv export : https://medium.com/@danny.pule/export-json-to-csv-file-using-javascript-a0b7bc5b00d2
// TODO: make this proper i.e. remove multiple occurance  of filtering data fields in excel and csv
// reference for above paste this in Quokka
/* const headers = {
  id: 'Id',
  name: 'Name',
  description: 'Description',
  price: 'Price',
  category: 'Category',
};
console.log(headers);
const header = ['Id', 'Name', 'Description', 'Price', 'Category', 'hh'];
const fields = ['id', 'name', 'description', 'price', 'category',];
console.log(header)
console.log(fields)
let tempObj = {}
for (index in fields) {
  tempObj[fields[index]] = header[index] || fields[index];
}

console.log(tempObj)

let f = [];
let h = [];
for (field in headers) {
  f.push(field);
  h.push(headers[field]);
}

console.log(f)
console.log(h)
*/

import { parseTime } from '@/utils';

export function formatJson(filterVal, jsonData) {
  return jsonData.map(v =>
    filterVal.map(j => {
      if (j === 'timestamp') {
        return parseTime(v[j]);
      } else {
        return v[j];
      }
    })
  );
}

export function exportWithFieldsToExcel(header, fields, dataToBeExported, filename, loader = '') {
  loader = true;
  import('@/vendor/Export2Excel').then(exportToExcel => {
    const data = formatJson(fields, dataToBeExported);
    exportToExcel.export_json_to_excel({
      header: header,
      data,
      filename: filename,
    });
    loader = false;
  });
}

// ! Data provided here is not filtered for csv, i.e. ',' are not removed
export function convertToCSV(objArray) {
  var array = typeof objArray !== 'object' ? JSON.parse(objArray) : objArray;
  var str = '';

  for (var i = 0; i < array.length; i++) {
    var line = '';
    for (var index in array[i]) {
      if (line !== '') {
        line += ',';
      }
      line += array[i][index];
    }
    str += line + '\r\n';
  }
  return str;
}

export function exportCSVFile(header, fields, items, fileTitle) {
  const headersObj = {};
  for (const index in fields) {
    headersObj[fields[index]] = header[index] || fields[index];
  }

  // ? filter items fields according to header
  const filteredItems = items.map(item => {
    const localCard = {};
    for (const field of fields) {
      localCard[field] = item[field];
    }
    return localCard;
  });
  if (headersObj) {
    filteredItems.unshift(headersObj);
  }
  // Convert Object to JSON
  var jsonObject = JSON.stringify(filteredItems);
  var csv = convertToCSV(jsonObject);
  var exportedFilenmae = fileTitle + '.csv' || 'export.csv';
  var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
  if (navigator.msSaveBlob) { // IE 10+
    navigator.msSaveBlob(blob, exportedFilenmae);
  } else {
    var link = document.createElement('a');
    if (link.download !== undefined) { // feature detection
      // Browsers that support HTML5 download attribute
      var url = URL.createObjectURL(blob);
      link.setAttribute('href', url);
      link.setAttribute('download', exportedFilenmae);
      link.style.visibility = 'hidden';
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    }
  }
}
