
$(document).ready(function()
{
    Reload();
    $.ajax({url : "https://covid-19.simone36050.it/api/update"});
    $(".reg").click(function () 
    { 
         if($(this).hasClass("v"))
             {
                $(".reg").removeClass("a").addClass("v");
                $(this).removeClass("v").addClass("a");
                Reload();
             }              
        else
              {
                $(this).removeClass("a").addClass("v");
                Reload();
              }       
    });
});


function Reload()
{
    var reg = GetRegions();
    if(reg != "")
        {
            
            var pro = GetProvinces(reg);
            var nam = GetNames(reg);
            var j = GetJson(pro);
            Graph(j,pro,nam);
        }
}

function GetRegions()
{
    var p = document.getElementsByClassName("reg a")[0].value;
    return p;
}

function GetJson(pro)
{
    var link = "https://covid-19.simone36050.it/api/provinces?";
    for(var i = 0; i < pro.length -1; i++)
        {
            link += "provinces[]=" + pro[i] + "&";
        }
    var r = "";
    $.ajax(
        {
            url : link,
            async:false,
            success : function (data,stato) 
            {
                r = data;
            },
            error : function (richiesta,stato,errori) 
            {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
            }
        });
    return r;
}

function GetProvinces(reg)
{
    var r = "";
    $.ajax(
        {
            url : "https://covid-19.simone36050.it/api/area?regions[]=" + reg,
            async:false,
            success : function (data,stato) 
            {
                r = data;
            },
            error : function (richiesta,stato,errori) 
            {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
            }
        });
        var pro = "";

        for (var i = 0; i < r[0].provinces.length; i++) 
        {
            pro += r[0].provinces[i].id + "*";
        }
    pro = pro.split("*")
    return pro;
}

function Graph(j,pro,nam) {
    var d = [];
    var dp = [];
    var key = [];    
    for(var i = 0; i < pro.length - 1; i++)
        {
            dp = [];
            key = []; 
            for(var k in j[pro[i]]) {key.push(k);}
            for(var c = 0; c < key.length;c++)
                {
                    dp.push({x:c + 1,y:j[pro[i]][key[c]]["cases"]});
                }
            d.push ({type: "line",name:nam[i],showInLegend: true,markerSize: 0,dataPoints: dp});
        }
    var chart = new CanvasJS.Chart("grafico",
    {        
        axisX:
        {
            title:"Giorni Trascorsi",
        },
        axisY:
        {
            title:"Casi Totali",
        },
        data:  d
        });

    chart.render();

  }


function GetNames(reg)
{
    var r = "";
    $.ajax(
        {
            url : "https://covid-19.simone36050.it/api/area?regions[]=" + reg,
            async:false,
            success : function (data,stato) 
            {
                r = data;
            },
            error : function (richiesta,stato,errori) 
            {
                alert("E' evvenuto un errore. Il stato della chiamata: "+stato);
            }
        });
        var nam = "";

        for (var i = 0; i < r[0].provinces.length; i++) 
        {
            nam += r[0].provinces[i].name + "*";
        }
    nam = nam.split("*")
    return nam;
}















