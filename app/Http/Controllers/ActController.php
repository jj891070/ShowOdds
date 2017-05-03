<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActController extends Controller
{
  public function vi()
  {
          return view('act');
  }
  public function index(){

         
          $ch1=curl_init();
          $ch2=curl_init();
          set_time_limit(0);//設定網頁timeout的時間無限大
          $options1 = array(
                   CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetMarkets',
                   CURLOPT_POST           => true,
                   CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&sport_type=1&market_type=t',
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT      => "Google Bot",
                   //CURLOPT_SSL_VERIFYHOST => 0,
                   //CURLOPT_SSL_VERIFYPEER => 0,
          );

          $options2 = array(
                   CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetEvents',
                   CURLOPT_POST           => true,
                   CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&sport_type=1&market_type=t',
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT      => "Google Bot",
                   //CURLOPT_SSL_VERIFYHOST => 0,
                   //CURLOPT_SSL_VERIFYPEER => 0,
          );
          //data[0]=GetMarket的頁面原始碼//
          curl_setopt_array($ch1, $options1);
          $data[0]=curl_exec($ch1);
          curl_close($ch1);
          //------------end-------------//

          //data[1]=GetEvent的頁面原始碼//
          curl_setopt_array($ch2, $options2);
          $data[1]=curl_exec($ch2);
          curl_close($ch2);
          //------------end-------------//
          
          //取EventID,LeagueID，HomeID，AwayID
          $teamName=[];
          $events = json_decode($data[1], true);
          $events = collect($events['Data']['events']);
          $events = $events->map(function ($value, $key) {
            return [
              'EventID' => $value['EventID'],
              'LeagueID' => $value['LeagueID'],
              'HomeID' => $value['HomeID'],
              'AwayID' => $value['AwayID'],
            ];
          });

          //------------end-------------//

           //取EventID,BetType,MarketStatus,Selections
          $markets = json_decode($data[0], true);
          $markets = collect($markets['Data']['markets']);
          $markets = $markets->map(function ($value, $key) {
            return [
              'EventID' => $value['EventID'],
              'BetType' => $value['BetType'],
              'MarketStatus' => $value['MarketStatus'],
              'Selections' => $value['Selections'],
            ];
          });
        
          //------------end-------------//
        

          //取出BetType=1,BetType=7,BetType=2,BetType=3,BetType=8
          $filterMarketBetType1= [];//讓分
          $filterMarketBetType7= [];//讓分上半場
          $filterMarketBetType2= [];//單雙
          $filterMarketBetType3= [];//大小
          $filterMarketBetType8= [];//大小上半場
          for ($i=0; $i < count($events); $i++) {
              $filterMarketBetType1[$i]="";
              $filterMarketBetType7[$i]="";
              $filterMarketBetType2[$i]="";
              $filterMarketBetType3[$i]="";
              $filterMarketBetType8[$i]="";
              //------------------------------------------
              $tmp_markets2=$markets->where('EventID',$events[$i]['EventID']);
              $tmp_markets=$tmp_markets2->where('BetType',1);
              if (empty($tmp_markets)) {
                $filterMarketBetType1[$i]="";
              }else{
                $num=0;
                foreach ($tmp_markets as $key => $tmp_market) {
                  $filterMarketBetType1[$i][$num]=$tmp_markets[$key];
                  $num++;
                }
              }
              //------------------------------------------
              $tmp_markets2=$markets->where('EventID',$events[$i]['EventID']);
              $tmp_markets=$tmp_markets2->where('BetType',7);
              if (empty($tmp_markets)) {
                $filterMarketBetType7[$i]="";
              }else{
                $num=0;
                foreach ($tmp_markets as $key => $tmp_market) {
                  $filterMarketBetType7[$i][$num]=$tmp_markets[$key];
                  $num++;
                }
              }
              //------------------------------------------
              $tmp_markets2=$markets->where('EventID',$events[$i]['EventID']);
              $tmp_markets=$tmp_markets2->where('BetType',2);
              if (empty($tmp_markets)) {
                $filterMarketBetType2[$i]="";
              }else{
                $num=0;
                foreach ($tmp_markets as $key => $tmp_market) {
                  $filterMarketBetType2[$i][$num]=$tmp_markets[$key];
                  $num++;
                }
              }
              //------------------------------------------
              $tmp_markets2=$markets->where('EventID',$events[$i]['EventID']);
              $tmp_markets=$tmp_markets2->where('BetType',3);
              if (empty($tmp_markets)) {
                $filterMarketBetType3[$i]="";
              }else{
                $num=0;
                foreach ($tmp_markets as $key => $tmp_market) {
                  $filterMarketBetType3[$i][$num]=$tmp_markets[$key];
                  $num++;
                }
              }
             //------------------------------------------
             $tmp_markets2=$markets->where('EventID',$events[$i]['EventID']);
              $tmp_markets=$tmp_markets2->where('BetType',8);
              if (empty($tmp_markets)) {
                $filterMarketBetType8[$i]="";
              }else{
                $num=0;
                foreach ($tmp_markets as $key => $tmp_market) {
                  $filterMarketBetType8[$i][$num]=$tmp_markets[$key];
                  $num++;
                }
              }
          }

          $filterMarketBetType1= collect($filterMarketBetType1);//讓分
          $filterMarketBetType7= collect($filterMarketBetType7);//讓分上半場
          $filterMarketBetType2= collect($filterMarketBetType2);//單雙
          $filterMarketBetType3= collect($filterMarketBetType3);//大小
          $filterMarketBetType8= collect($filterMarketBetType8);//大小上半場
          dd($filterMarketBetType7);
          //------------end-------------//

          //賠率值//

          $odd=[];
          $point=[];
          for ($i=0; $i <count($filterMarketBetType1) ; $i++) {
            //全場讓分
            if($filterMarketBetType1[$i]!=""){
              $a=$filterMarketBetType1[$i][0]['Selections'];
              $a=collect($a);
              $odd[0][0][$i][0]=$a[0]['Price'];//主隊賠率
              $odd[0][0][$i][1]=$a[0]['Point'];//主隊球投
              $odd[0][0][$i][2]=$a[1]['Price'];//客隊賠率
              $odd[0][0][$i][3]=$filterMarketBetType1[$i][0]['MarketStatus'];
            }else{
              $odd[0][0][$i]="";
            }
            //-----------------------------------------------
            if($filterMarketBetType7[$i]!=""){
              $a=$filterMarketBetType7[$i][0]['Selections'];
              $a=collect($a);
              $odd[0][1][$i][0]=$a[0]['Price'];//主隊賠率
              $odd[0][1][$i][1]=$a[0]['Point'];//主隊球投
              $odd[0][1][$i][2]=$a[1]['Price'];//客隊賠率
              $odd[0][1][$i][3]=$filterMarketBetType7[$i][0]['MarketStatus'];
            }else{
              $odd[0][1][$i]="";
            }
            //單雙
            if($filterMarketBetType2[$i]!=""){
              $a=$filterMarketBetType2[$i][0]['Selections'];
              $a=collect($a);
              $odd[1][0][$i][0]=$a[0]['Price'];//主隊賠率
              $odd[1][0][$i][1]=$a[0]['Point'];//主隊球投
              $odd[1][0][$i][2]=$a[1]['Price'];//客隊賠率
              $odd[1][0][$i][3]=$filterMarketBetType2[$i][0]['MarketStatus'];
            }else{
              $odd[1][0][$i]="";
            }
            //全場大小
            if($filterMarketBetType3[$i]!=""){
              $a=$filterMarketBetType3[$i][0]['Selections'];
              $a=collect($a);
              $odd[2][0][$i][0]=$a[0]['Price'];//主隊賠率
              $odd[2][0][$i][1]=$a[0]['Point'];//主隊球投
              $odd[2][0][$i][2]=$a[1]['Price'];//客隊賠率
              $odd[2][0][$i][3]=$filterMarketBetType3[$i][0]['MarketStatus'];
            }else{
              $odd[2][0][$i]="";
            }
            //-----------------------------------------------
            if($filterMarketBetType8[$i]!=""){
              $a=$filterMarketBetType8[$i][0]['Selections'];
              $a=collect($a);
              $odd[2][1][$i][0]=$a[0]['Price'];//主隊賠率
              $odd[2][1][$i][1]=$a[0]['Point'];//主隊球投
              $odd[2][1][$i][2]=$a[1]['Price'];//客隊賠率
              $odd[2][1][$i][3]=$filterMarketBetType8[$i][0]['MarketStatus'];
            }else{
              $odd[2][1][$i]="";
            }
          }
          //dd($odd);
          //------------end-------------//
         
          /*
          foreach ($events as $key => $event) {


            $filterMarket[$event]=
          }
          dd($markets[0]);
          */

          /*
          //使用正則去取data[1]值
          preg_match_all('(\"LeagueID\":[0-9]+)',$data[1],$teamID[0]);
          $teamID[0]=preg_replace('/\"LeagueID\":/','',$teamID[0][0]);
          preg_match_all('(\"HomeID\":[0-9]+)',$data[1],$teamID[1]);
          $teamID[1]=preg_replace('/\"HomeID\":/','',$teamID[1][0]);
          preg_match_all('(\"AwayID\":[0-9]+)',$data[1],$teamID[2]);
          $teamID[2]=preg_replace('/\"AwayID\":/','',$teamID[2][0]);
          preg_match_all('(\"LeagueID\":[0-9]+,\"HomeID\":[0-9]+,\"AwayID\":[0-9]+)',$data[1],$teamID[3]);
          */

          //取球隊及聯賽名稱//
          for ($i=0; $i <count($events) ; $i++) {
            $ch3=curl_init();
            $options3 = array(
                     CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetLeagueName',
                     CURLOPT_POST           => true,
                     CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&league_id='.$events[$i]['LeagueID'],
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_USERAGENT      => "Google Bot",
            );
            curl_setopt_array($ch3, $options3);
            $data[2]=curl_exec($ch3);
            curl_close($ch3);
            preg_match_all('(\"lang\":\"ch\",\"name\":\".*?\")',$data[2],$teamName[0][$i]);
            $teamName[0][$i]=preg_replace('(\"lang\":\"ch\",\"name\":\")','',$teamName[0][$i][0][0]);
            $teamName[0][$i]=preg_replace('(\")','',$teamName[0][$i]);
            //------------------------------------------
            $ch3=curl_init();
            $options3 = array(
                     CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetTeamName',
                     CURLOPT_POST           => true,
                     CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&team_id='.$events[$i]['HomeID'],
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_USERAGENT      => "Google Bot",
            );
            curl_setopt_array($ch3, $options3);
            $data[2]=curl_exec($ch3);
            curl_close($ch3);
            preg_match_all('(\"lang\":\"ch\",\"name\":\".*?\")',$data[2],$teamName[1][$i]);
            $teamName[1][$i]=preg_replace('(\"lang\":\"ch\",\"name\":\")','',$teamName[1][$i][0][0]);
            $teamName[1][$i]=preg_replace('(\")','',$teamName[1][$i]);
            //------------------------------------------
            $ch3=curl_init();
            $options3 = array(
                     CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetTeamName',
                     CURLOPT_POST           => true,
                     CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&team_id='.$events[$i]['AwayID'],
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_USERAGENT      => "Google Bot",
            );
            curl_setopt_array($ch3, $options3);
            $data[2]=curl_exec($ch3);
            curl_close($ch3);
            preg_match_all('(\"lang\":\"ch\",\"name\":\".*?\")',$data[2],$teamName[2][$i]);
            $teamName[2][$i]=preg_replace('(\"lang\":\"ch\",\"name\":\")','',$teamName[2][$i][0][0]);
            $teamName[2][$i]=preg_replace('(\")','',$teamName[2][$i]);


          }
          //dd($teamName[2]);
          //------------end-------------//

          //$OutputLeagueName變數league名稱整理,KEY與VALUE的轉換//
          $OutputTotal=[];
          $OutputLeagueName=[];
          $OutputTeamName=[];
          $tmp_ID=array_flip($teamName[0]);
          $num=0;
          foreach ($tmp_ID as $key => $value) {
              $OutputLeagueName[$num]=$key;
              $num++;
          }
          //dd($output);
          //------------end-------------//
          //dd($teamName[2]);
          //將各隊名稱分類至league裡面
          for ($i=0; $i < count($OutputLeagueName) ; $i++) {
            $num=0;
            for ($j=0; $j < count($events); $j++) {
              if ($teamName[0][$j]==$OutputLeagueName[$i]) {
                $OutputTeamName[$i][$num]=$teamName[1][$j];
                $num++;
                $OutputTeamName[$i][$num]=$teamName[2][$j];
                $num++;
              }
            }
          }
          //dd($odd);
          //------------end-------------//

          //-----------------[總結]----------------------
          //$OutputTotal[0]=所有聯賽的名稱
          //$OutputTotal[1]=以聯賽來分類，報含著所有teamname，兩個兩個一組
          //$OutputTotal[2]=讓球、單雙、大小，主隊賠率、主隊球投、客隊賠率
          //$OutputTotal[3]=market_status的狀態，0=closed、1=running
          $OutputTotal[0]=$OutputLeagueName;
          $OutputTotal[1]=$OutputTeamName;
          $OutputTotal[2]=$odd;
          //dd($OutputTotal);
          //league_name放入$OutputTotal,team_name放入$OutputTotal
          /*
          for ($i=0; $i < count($OutputLeagueName); $i++) {
            $OutputTotal[0][$i]=$OutputLeagueName[$i];
            for ($j=0; $j < count($OutputTeamName[$i]); $j++) {
              $OutputTotal[1][$i][$j]=$OutputTeamName[$i][$j];
            }
          }

          //

          for ($i=0; $i <count($odd) ; $i++) {
            $OutputTotal[2][$i]=$odd[$i];

          }
          */
          
          //dd($OutputTotal);
          //------------end-------------//
          /*
          for ($i=0; $i < count($output); $i++) {
            for ($j=0; $j < count($events); $j++) {
               if ($output[0][$i]==$teamName[$j]) {
                   $output[$j]
               }
             }

          }
          */
          //dd($teamName);

          /*
          $teamID=array_flip($teamID[0]);//key與值得轉換
          for ($i=0; $i <count($output[0]) ; $i++) {
            $options3 = array(
                     CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetLeagueName',
                     CURLOPT_POST           => true,
                     CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&league_id='.$teamID[$i],
                     CURLOPT_RETURNTRANSFER => true,
                     CURLOPT_USERAGENT      => "Google Bot",
            );

          }
          */



          /*
          //清掉重複的LeagueID
          $tmp_num=0;
          $tmp_array=[];
          $val=0;
          for ($i=0; $i < count($output[0]); $i++) {
            $tmp_array[$i]='';
          }

          for ($i=0; $i < count($output[0]); $i++) {
            for ($j=0; $j <count($output[0]) ; $j++) {
              if ($tmp_array[$j]==$teamID[0][$i]) {
                $val=1;
                break;
              }else {
                $val=0;
              }
            }
            if ($val==0) {
              $tmp_array[$tmp_num]=$teamID[0][$i];
              $tmp_num++;
            }
          }
          */


          /*


          $options3 = array(
                   CURLOPT_URL            => 'http://192.168.113.7:8086/api/GetTeamName',
                   CURLOPT_POST           => true,
                   CURLOPT_POSTFIELDS     => 'vendor_id=jP8MMqgExP0&team_id='.$teamID,
                   CURLOPT_RETURNTRANSFER => true,
                   CURLOPT_USERAGENT      => "Google Bot",
                   //CURLOPT_SSL_VERIFYHOST => 0,
                   //CURLOPT_SSL_VERIFYPEER => 0,
          );
          */

    return view('act',['data'=>$OutputTotal]);
  }
    //
}
