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

export function exportWithFieldsToExcel(fields, header, dataToBeExported, filename, loader = '') {
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
