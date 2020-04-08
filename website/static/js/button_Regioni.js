$(document).ready(function()
{
    Reload();
    $.ajax({url : "https://covid-19.simone36050.it/api/update"});
    $(".b-1").click(function () 
    { 
        $.ajax({url : "https://covid-19.simone36050.it/api/area"});
        if($(this).hasClass("v"))
             {
                $(".b-1").removeClass("a").addClass("v");
                $(this).removeClass("v").addClass("a");
                Reload();
             }              
        else
              {
                $(this).removeClass("a").addClass("v");
                Reload();
              }
    });
    $(".reg").click(function () 
    { 
         if($(this).hasClass("v"))
             {
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
    var par = GetData();
    var reg = GetRegions();
    
    if(par != "" && reg != "")
        {
            var j = GetJson(par,reg);
            Graph(j,par,reg);
        }
}

function GetData()
{
    var p = document.getElementsByClassName('b-1 a')[0].value;
    return p;
}

function GetRegions()
{
    var p = "";
    
    for (var i = 0; i < document.getElementsByClassName('reg a').length; i++) 
    {
        p = p + document.getElementsByClassName('reg a')[i].value + "*";
    }
    p = p.split("*");
    return p;
}
 
function GetJson(par,reg)
{
    var link = "https://covid-19.simone36050.it/api/regions?";
    link += "data[]=" + par + "&";
    for(var i = 0; i < reg.length -1; i++)
        {
            link += "regions[]=" + reg[i] + "&";
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

function Graph(j,par,reg) {
    var d = [];
    var dp = [];
    var key = [];    
    for(var i = 0; i < reg.length - 1; i++)
        {
            dp = [];
            key = []; 
            for(var k in j[reg[i]]) {key.push(k);}
            for(var c = 0; c < key.length;c++)
                {
                    dp.push({x:c + 1,y:j[reg[i]][key[c]][par]});
                }
            d.push ({type: "line",name:Regions(parseInt(reg[i])),showInLegend: true,markerSize: 0,dataPoints: dp});
        }
    var chart = new CanvasJS.Chart("grafico",
    {        
        axisX:
        {
            title:"Grioni Trascorsi",
        },
        axisY:
        {
            title:document.getElementsByClassName('b-1 a')[0].innerHTML,
        },
        data:  d
        });

    chart.render();
  }

function Regions(c)
{
    switch(c)
        {
            case 1:
                return "Piemonte";
                break;
            case 2:
                return "Valle d'Aosta";
                break;
            case 3:
                return "Lombardia";
                break;
            case 5:
                return "Veneto";
                break;
            case 6:
                return "Friuli Venezia Giulia";
                break;
            case 7:
                return "Liguria";
                break;
            case 8:
                return "Emilia Romagna";
                break;
            case 9:
                return "Toscana";
                break;
            case 10:
                return "Umbria";
                break;
            case 11:
                return "Marche";
                break;
            case 12:
                return "Lazio";
                break;
            case 13:
                return "Abruzzo";
                break;
            case 14:
                return "Molise";
                break;
            case 15:
                return "Campania";
                break;
            case 16:
                return "Puglia";
                break;
            case 17:
                return "Basilicata";
                break;
            case 18:
                return "Calabria";
                break;
            case 19:
                return "Sicilia";
            case 20:
                return "Sardegna";
                break;
            case 40:
                return "Bolzano PA";
                break;
            case 41:
                return "Trento PA";
                break;
        }
}

  


















