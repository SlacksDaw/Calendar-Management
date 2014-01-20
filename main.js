function mysqlEsc (str) {
        return str.replace(/[\0\x08\x09\x1a\n\r"'\\\%]/g, function (char) {
                switch (char) {
                        case "\0":
                                return "\\0";
                        case "\x08":
                                return "\\b";
                        case "\x09":
                                return "\\t";
                        case "\x1a":
                                return "\\z";
                        case "\n":
                                return "\\n";
                        case "\r":
                                return "\\r";
                        case "\"":
                        case "'":
                        case "\\":
                        case "%":
                                return "\\"+char; // prepends a backslash to backslash, percent,
                                                                  // and double/single quotes
                }
        });
}

function dupe(row) {
        row = row || addRow('nope');
        
        var insBut = document.createElement('button');
                insBut.innerText = 'Done';
                insBut.addEventListener('click', function() {
                        modRow(this.parentNode.parentNode, 'insert');
                        this.parentNode.parentNode.className = 'row';
                        $(this.parentNode.querySelectorAll('button')).show();
                        $(this).remove();
                });
        var nRow = row.cloneNode(true);
                nRow.className = 'row dupe';
                nRow.id = '';
                nRow.querySelector('input[name=id]').value = 'null';
                nRow.querySelector('input[name=appr]').value = 0;
                $(nRow.querySelector('.date')).prop('id' , '').prop('class', 'date').datepicker({ dateFormat: 'yy-mm-dd' });
                //nRow.querySelector('div').innerHTML = '';
                $(nRow.querySelectorAll('button')).hide();
                nRow.querySelector('div').insertBefore(insBut, nRow.querySelector('div').children[0]);



        $('#content').prepend(nRow);
        addHelpers();
        $(nRow.querySelector('input.performer')).focus();
}


function preview(row) {

        if (row.querySelector('p')) $(row.querySelector('p')).remove();
        else
                
                var newInp = extractDat(row)[1];
                if (newInp.extra.length > 1) newInp.phone = newInp.extra + ' ' + newInp.phone;
                var p = document.createElement('p');
                        p.innerHTML += newInp.performer + '&mdash;' + newInp.genre + ' at ' + newInp.venue + ', ' +newInp.town + ', ' + newInp.time + ', '  + newInp.cover + ', ' + newInp.phone;
                        

                        row.insertBefore(p, row.querySelector('div'));


        } 

gloDate = new Date();
function buildDate(date) {
        var dNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']
        var mNames = ['Janurary', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']
        
        var p = document.createElement('p');
        p.className = 'date';
        p.innerHTML = dNames[date.getDay()] + mNames[date.getMonth()] + date.getDate();
        return p;
}


function addRow(ele, manEle) {
        var content = document.getElementById('content');
        if (ele == 'nope') {
                ele = '{"subName":"","subEmail":"","performer":" ","genre":" ","venue":" ","town":" ","date":" ","startTime":" ","cover":" ","phone":" ","extra":"","url":"","appr":"0","id":"null"}';
                var man = true;
        }

        var tmp = JSON.parse(ele);
        var div = document.createElement('div');
        div.className = 'row';
        var approval = document.createElement('span');
                approval.addEventListener('click', function() {update(this.parentNode.parentNode, 'approve')});
        var buttons = document.createElement('div'); //Indenting here just to keep things straight
                var button = document.createElement('button');
                var appButton = document.createElement('button');
                var modButton = document.createElement('button');
                        modButton.innerText = 'Modify';
                        modButton.addEventListener('click', function() {update(this.parentNode.parentNode)});
                var contBut = document.createElement('button');
                        contBut.innerText = 'Contact';
                        contBut.addEventListener('click', function() {
                                var contact = this.parentNode.parentNode.querySelectorAll('.contact')
                                alert('Submitter: ' + contact[0].value + '\nEmail: ' + contact[1].value)
                        });
                var delButton = document.createElement('button');
                        delButton.innerText = 'Delete';
                        delButton.className = 'delete';

                        delButton.addEventListener('click', function() {modRow(this.value, 'delete')});
                var dupeButton = document.createElement('button');
                        dupeButton.innerText = 'Duplicate';
                        dupeButton.addEventListener('click', function() {dupe(this.parentNode.parentNode)});



        appButton.addEventListener('click', function() {update(this.parentNode.parentNode, 'approve')});
        appButton.innerText = 'Approve';
        appButton.className = 'approve';
                button.innerText = 'Preview';
                button.addEventListener('click', function() { preview(this.parentNode.parentNode)});
        if (tmp['performer'] == '') {
                var date = new Date(tmp['date']);
                if (date > gloDate) {
                        gloDate = date;
                        div.appendChild(buildDate(date));
                }
        } else {
                for (var i in tmp) {
                        var inp = document.createElement('input');
                        var span = document.createElement('span');
                        if (i == 'appr' || i == 'subName' || i == 'subEmail' || i == 'id') {
                                if (i == 'id') {
                                        appButton.value = tmp[i];
                                        delButton.value = tmp[i];
                                        approval.id = 'ap'+tmp[i];
                                        div.id = 'row'+tmp[i];
                                }
                                if (i == 'appr') {
                                        if (tmp[i] == '0') approval.className = 'nApproved';
                                        else approval.className = 'approved';
                                        approval.className += ' approval';
                                }

                                inp.className = 'hidden contact ';
                        } else 
                                span.innerHTML = i + ': ';
                                span.className = 'hidden';
                                inp.value = tmp[i];
                                inp.name = i;
                                inp.className += i;
                                div.appendChild(inp);
                        
                }
                buttons.appendChild(button);
                buttons.appendChild(appButton);
                buttons.appendChild(modButton);
                buttons.appendChild(delButton);
                buttons.appendChild(dupeButton);
                buttons.appendChild(contBut);
                buttons.appendChild(approval);
                div.appendChild(buttons);

        } if (!man) {
                content.appendChild(div);
        } else {return div}
}
function extractDat(row) {
        var content = 'check=1';
        var inp = row.querySelectorAll('input');
        var newInp = {
                performer:inp[2].value,
                genre:inp[3].value,
                venue:inp[4].value,
                town:inp[5].value,
                date:inp[6].value,
                time:inp[7].value,
                cover:inp[8].value,
                phone:inp[9].value,
                extra:inp[10].value,
                url:inp[11].value,
                approve:inp[12].value,
                id:inp[13].value
        }
        for (var name in newInp) {
                        content += '&' + name + '=' + encodeURIComponent(mysqlEsc(newInp[name]));
                }
        return [content, newInp];
}        

function update(row, act) {
        act = act || 'update';
        var appr = row.querySelector('.approval');
        var dat = extractDat(row);
        var apprs = {0:'nApproved', 1:'approved'};
        var curAppr = dat[1]['approve'];
        if (act == 'update') {
                appr.className = appr.className.replace('approved', 'nApproved');
                dat[0] += '&appr=0';
        }
        if (act == 'approve') {
                appr.className = appr.className.replace(apprs[curAppr], apprs[(curAppr ^= 1)]);
                dat[0] += '&appr=' + curAppr;
        }
        row.querySelector('.appr').value = curAppr;
        
        $.ajax({
                type: "POST",
                url: "update.php?action=" + act,
                data: dat[0],
                success: function(html){
                        $('#' + dirty['where']).css('background-color', 'white');
                        dirty['where'] = '';
                        dirty['clean'] = true;
                } 
        });                
}


var cachedData;
function search(query, metric, sort) {
        if (query == 'duplicates') var act = 'duplicates';
        else var act = 'search';
        if (!sort) var sort = [document.getElementById('sortBy').value, document.getElementById('sortOrd').value];
        if (!metric) var metric = document.getElementById("seMetric").value;
        var content = document.getElementById('content');
        while (content.firstChild) {
                content.removeChild(content.firstChild);
        }
        var sData;
        if (query !== '' && query !== 0 && query !== 1 && query !== 2) sData = 'sort=' + sort[0] + '&order=' + sort[1] + '&search=' + query + '&metric=' + metric;
        else if (query === 0) sData = 'search=0&metric=appr&sort=' + sort[0] + '&order=' + sort[1];
        else if (query === 1) {
                sData = cachedData;
                if (sort) sData = sData.replace(/(sort=).*?&/, 'sort=' + sort[0] + '&');
                if (sData.indexOf('ASC') > 0) sData = sData.replace('ASC', 'DESC');
                else sData = sData.replace('DESC', 'ASC');
        }
        if (!sData) sData = cachedData;
        cachedData = sData;
        console.log(sData);
        $.ajax({
                type: "POST",
                url: "update.php?action=" + act,
                data: mysqlEsc(sData),
                success: function(data){
                        results = data.split('^');
                        for (var i in results) {
                                addRow(results[i])
                        }
                        

                        //results.forEach(addRow);
                        // console.log(addRow(results));

                } 
        });
        setTimeout(function(){
                addHelpers();
        },1000);
}


function modRow(id, act) {
        if (act == 'insert') {
                var cont = extractDat(id)[0];
        } else {
                var row = $('#row'+id)[0];
                var cont = 'id=' + id;
        }

        if (act === 'delete') { //This has caused me a lot of grief for not adding it sooner...
                var con = confirm('Are you sure you want to delete ' + $(row).find('.performer').val() + ' ?')
                if (con === false) return false;
        }
        
        console.log(cont);
        $.ajax({
                type: "POST",
                url: "update.php?action=" + act,
                data: cont,
                success: function(html){
                        console.log(act + 'd');
                        if (act == 'approve') {
                                var appr = row.querySelector('.approval');
                                appr.className = appr.className.replace('nApproved', 'approved');
                        }
                        if (act == 'delete') $(row).remove();
                        if (act == 'insert') search(2);
                
                } 
        });
}
function addHelpers() {
        $('#content input').each(function(){ 
                if (this.value === ' ') {
                        this.value = '';
                } this.value = $(this).val().trim();
        });
        $('.date').datepicker({ dateFormat: 'yy-mm-dd' });
        $('#content input').on('change', function() {
                console.log(this, this.parentNode.id)
                dirty['clean'] = false;
                dirty['where'] = this.parentNode.id;
                $(this.parentNode).css('background-color', '#FFE680');
        })
        $('input.performer').each(function(){
                        $(this).autocomplete({
                                autoFocus: true,
                                source: function(req, responseFn) {
                                        var re = $.ui.autocomplete.escapeRegex(req.term);
                                        var matcher = new RegExp( "^" + re, "i" );
                                        var a = $.grep( Object.keys(performers), function(item,index){
                                                return matcher.test(item);
                                        });
                                        responseFn( a );
                                },
                                select: function (a, b) {
                                        if (performers[b.item.value] !== '') $(this.parentNode.querySelector("input[name=genre]")).val(performers[b.item.value]);
                                }
                        })
                })
        $('.town').each(function(){
                $(this).autocomplete({
                        autoFocus: true,
                        source: function(req, responseFn) {
                                var re = $.ui.autocomplete.escapeRegex(req.term);
                                var matcher = new RegExp( "^" + re, "i" );
                                var a = $.grep( cities, function(item,index){
                                        return matcher.test(item);
                                });
                                responseFn( a );
                        }
                })
        })

        $('.genre').each(function(){
                $(this).autocomplete({
                        autoFocus: true,
                        source: function(req, responseFn) {
                                var re = $.ui.autocomplete.escapeRegex(req.term);
                                var matcher = new RegExp( "^" + re, "i" );
                                var a = $.grep( genres, function(item,index){
                                        return matcher.test(item);
                                });
                                responseFn( a );
                        }
                })
        })

        $('input.venue').each(function(){
                        $(this).autocomplete({
                                autoFocus: true,
                                source: function(req, responseFn) {
                                        var re = $.ui.autocomplete.escapeRegex(req.term);
                                        var matcher = new RegExp( "^" + re, "i" );
                                        var a = $.grep( venues, function(item,index){
                                                return matcher.test(item);
                                        });
                                        responseFn( a );
                                },
                                select: function (a, b) {
                                        $(this.parentNode.querySelector("input[name=phone]")).unmask().val(com[b.item.value]['phone']);
                                        $(this.parentNode.querySelector("input[name=town]")).val(com[b.item.value]['city']);
                                        var cov = $(this.parentNode.querySelector("input.cover"));
                                        $(cov).val(com[b.item.value]['cover']);
                                        //$(".state").val(com[b.item.value]['state']).prop('readonly', 'true');
                                }
                        })
                })

}

$(function(){


        window.onbeforeunload = function (evt) {
                if (dirty['clean'] !== true) {
                        var message = 'You have unsaved changes. Continue without saving?';
                        if (typeof evt == 'undefined') {
                                evt = window.event;
                        }
                        if (evt) {
                                evt.returnValue = message;
                        }
                        return message;
                } 
        }


        dirty = {clean:true, where:''};
        $("#search").keyup(function (e) {
                if (e.keyCode == 13) {
                   search(this.value, this.previousElementSibling.value)
                }});



        $('p.headers').each(function(){
                $(this).on('click', function(){
                        search(1, null, [this.className.split(' ')[1]])
                })
        })

        document.getElementById('seMetric').addEventListener('change', function() { 
                
                var sea = $('#search')[0];
                console.log(this.value + ' | ' + sea.className)
                if (this.value == 'date') {
                        $(sea).removeData('autocomplete');
                        $(sea).datepicker({ dateFormat: 'yy-mm-dd' }); 
                        console.log('add');
                } else if (sea.className.indexOf('hasDatepicker') !== -1) {
                        $(sea).datepicker('destroy');
                        console.log('remove');
                } else if (this.value == 'venue')
                console.log('auto');
                $(this).next().autocomplete({
                        autoFocus: true,
                        source: function(req, responseFn) {
                                var re = $.ui.autocomplete.escapeRegex(req.term);
                                var matcher = new RegExp( "^" + re, "i" );
                                var a = $.grep( venues, function(item,index){
                                        return matcher.test(item);
                                });
                                responseFn( a );
                        },
                        select: function (a, b) {
                                   search(b.item.value, this.previousElementSibling.value)
                                }
                })
        })
        getEm();
});