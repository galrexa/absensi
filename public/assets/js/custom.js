
/*
  show hide text
  example in field password::

  <div class="input-group">
    <input type="password" name="password" id="password" value="" class="form-control" autocomplete="off">
    <span class="input-group-text"><i id="iPassword" class="fa fa-eye" onclick="showHidePassword('#password', '#iPassword')"></i></span>
  </div>
*/
function showHidePassword($fieldPass, $iconPass)
{
    var iPassword = $($iconPass).attr('class')
    switch (iPassword){
        case 'fa fa-eye-slash':
            $($fieldPass).prop('type', 'password')
            $($iconPass).prop('class', 'fa fa-eye').prop('title', 'Lihat text')
            break;
        default:
            $($fieldPass).prop('type', 'text')
            $($iconPass).prop('class', 'fa fa-eye-slash').prop('title', 'Sembunyikan text')
        break;
    }
}

      function formatWaktu(wkt='', act='', st='')
      {
          let d = new Date(wkt);
          let months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
          let days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum`at', 'Sabtu'];
          let jam = '';
          if(st!=''){
              jam = wkt.substring(10);  // d.getHours()+':'+d.getMinutes()+':'+d.getSeconds(); // +':'+d.getMilliseconds()
          }
          var rs = '';
          switch(act) {
              case 1:
                  rs = days[d.getDay()]+', '+d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear()+' '+jam;
                  break;
              case 2:
                  rs = d.getDate()+' '+months[d.getMonth()]+' '+d.getFullYear()+' '+jam;
                  break;
              case 3:
                  rs = months[d.getMonth()]+' '+d.getFullYear();
                  break;
              case 4:
                  rs = months[d.getMonth()];
                  break;
              case 5:
                  rs = d.getFullYear();
                  break;
              case 6:
                  // rs = d.getDate()+'/'+d.getMonth()+'/'+d.getFullYear()+' '+jam;
                  rs = returnDigitNumber(d.getDate(),2)+'/'+returnDigitNumber((d.getMonth()+1),2)+'/'+d.getFullYear()+' '+jam;
                  // rs = d.getDate()+'/'+wkt.substring(3,2)+'/'+d.getFullYear()+' '+jam;
                  break;
              default:
                  rs = wkt;
                  break;
          }
          return rs;
      }


      function number_format(number, decimals, decPoint, thousandsSep) { 
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
        var n = !isFinite(+number) ? 0 : +number
        var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
        var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
        var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
        var s = ''

        var toFixedFix = function (n, prec) {
          var k = Math.pow(10, prec)
          return '' + (Math.round(n * k) / k)
          .toFixed(prec)
        }

        // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
        if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
        }
        if ((s[1] || '').length < prec) {
          s[1] = s[1] || ''
          s[1] += new Array(prec - s[1].length + 1).join('0')
        }

        return s.join(dec)
      }



      function returnDigitNumber(value, digit){
        var rs = ""
        let lengthValue = String(value).length
        if (lengthValue == digit) {
          rs = value
        }else{
          for (let i = 0; i < (digit - lengthValue); i++) {
            rs += "0"
          }
          rs += value
        }
        return rs
      }