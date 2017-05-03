<!DOCTYPE html>
<html>
  <head>
    <style>
        table, td, th {
            border: 1px solid #ddd;
            text-align: left;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            padding: 15px;
        }
        tr:hover{background-color:#f5f5f5}

    </style>
    <meta charset="utf-8">
    <meta http-equiv="refresh" content="60">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <title>ShowOdds</title>
  </head>

  <body>
     
              <div>
                  <input type="button" style="background-color:blue;color:white;" value="Refresh Page" onClick="window.location.reload()">
              </div>
              <div>
                  <table>
                      <thead>
                         <tr>
                           <th>赛事</th>
                           <th>全場讓球</th>
                           <th>上半場讓球</th>
                           <th>單雙</th>
                           <th>全場大/小</th>
                           <th>上半場大/小</th>
                         <tr>
                         
                      </thead>
                      <tbody>
                        <!--輸出data裡的所有資料-->
                        <?php
                            $num2=0;
                            $num3=0;//MarketStatus用變數
                            for ($i=0; $i < count($data[0]); $i++) {
                               echo '<tr>';
                               echo '<td colspan="8">' . '<span style="color:blue;font-weight:bold">'. $data[0][$i] . '</span>' . '</td>';
                               echo '</tr>';
                               for ($j=0; $j < count($data[1][$i]); $j++) {
                                 $num1=$j*2;
                                 if ($num1==count($data[1][$i])) {
                                   break;
                                 }
                                 echo '<tr>';
                                 //-----------------------------輸出隊伍名稱-----------------------------
                                 echo '<td>';
                                 if($data[2][0][0][$num2]!=""){
                                    if ($data[2][0][0][$num2][1]<0) {
                                       echo '<div>' . '<span style="color:red;font-weight:bold">' . $data[1][$i][$num1] . '</span>' . '</div>';
                                       echo '<div>' . $data[1][$i][$num1+1] . '</div>';
                                     }else if ($data[2][0][0][$num2][1]==0){
                                       echo '<div>' . $data[1][$i][$num1] . '</div>';
                                       echo '<div>' . $data[1][$i][$num1+1] . '</div>';
                                     }else{
                                       echo '<div>' . $data[1][$i][$num1] . '</div>';
                                       echo '<div>' . '<span style="color:red;font-weight:bold">' . $data[1][$i][$num1+1] . '</span>' . '</div>';
                                     }
                                 }else{
                                   echo '<div>' . $data[1][$i][$num1] . '</div>';
                                   echo '<div>' . $data[1][$i][$num1+1] . '</div>';
                                 }
                                 echo '</td>';
                                 //-----------------------------輸出讓分-----------------------------
                                 echo '<td>';
                                   if($data[2][0][0][$num2]!=""){
                                       //以下的if分別是主隊的球投是零、是小於零、是大於零，的情況
                                       if($data[2][0][0][$num2][1]==0){
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][0][$num2][0] . '</div>';
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][2] . '</div>';
                                           

                                       }else if($data[2][0][0][$num2][1]<0){
                                           $data[2][0][0][$num2][1]=abs($data[2][0][0][$num2][1]);
                                           $point=$data[2][0][0][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if($point%5!=0){
                                              $OutPoint1=$data[2][0][0][$num2][1]-0.25;
                                              $OutPoint2=$data[2][0][0][$num2][1]+0.25;
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . '-' . $OutPoint2. "&nbsp&nbsp&nbsp" . $data[2][0][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][2] . '</div>';
                                              
                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][2] . '</div>';
                                              
                                           }
                                       }else{
                                           $point=$data[2][0][0][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if ($point%5!=0) {
                                               $OutPoint1=$data[2][0][0][$num2][1]-0.25;
                                               $OutPoint2=$data[2][0][0][$num2][1]+0.25;
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][0] . '</div>';
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . "-" . $OutPoint2 . "&nbsp&nbsp&nbsp" . $data[2][0][0][$num2][2] . '</div>';
                                               

                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][0][$num2][2] . '</div>';
                                              

                                           }
                                       }
                                       //----------MarketStatus---------//
                                       
                                       echo '<div id="status'. $num3 .'" style="display: none;" align="right">' . $data[2][0][0][$num2][3] . '</div>';
                                       $num3++;
                                       //----------end----------//
                                   }else{
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                   }
                                 echo '</td>';
                                 //-----------------------------輸出上半場讓分-----------------------------
                                 echo '<td>';
                                   if($data[2][0][1][$num2]!=""){
                                       //以下的if分別是主隊的球投是零、是小於零、是大於零，的情況
                                       if($data[2][0][1][$num2][1]==0){
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][1][$num2][0] . '</div>';
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][2] . '</div>';
                                           
                                       }else if($data[2][0][1][$num2][1]<0){
                                           $data[2][0][1][$num2][1]=abs($data[2][0][1][$num2][1]);
                                           $point=$data[2][0][1][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if($point%5!=0){
                                              $OutPoint1=$data[2][0][1][$num2][1]-0.25;
                                              $OutPoint2=$data[2][0][1][$num2][1]+0.25;
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . '-' . $OutPoint2. "&nbsp&nbsp&nbsp" . $data[2][0][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][2] . '</div>';

                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][2] . '</div>';
                                           }
                                       }else{
                                           $point=$data[2][0][1][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if ($point%5!=0) {
                                               $OutPoint1=$data[2][0][1][$num2][1]-0.25;
                                               $OutPoint2=$data[2][0][1][$num2][1]+0.25;
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][0] . '</div>';
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . "-" . $OutPoint2 . "&nbsp&nbsp&nbsp" . $data[2][0][1][$num2][2] . '</div>';
                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][0][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][0][1][$num2][2] . '</div>';
                                           }
                                       }
                                       //----------MarketStatus---------//
                                       
                                       echo '<div id="status'. $num3 .'" style="display: none;" align="right">' . $data[2][0][1][$num2][3] . '</div>';
                                       $num3++;
                                       //----------end----------//
                                   }else{
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                   }
                                 echo '</td>';
                                 //-----------------------------輸出單雙-----------------------------
                                 echo '<td>';
                                   if($data[2][1][0][$num2]!=""){
                                       echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][1][0][$num2][0] . '</div>';
                                       //echo '<div align="right">' . $data[2][1][0][$num2][1] . '</div>';
                                       echo '<div align="right">' . $data[2][1][0][$num2][2] . '</div>';
                                       //----------MarketStatus---------//
                                       
                                       echo '<div id="status'. $num3 .'" style="display: none;" align="right">' . $data[2][1][0][$num2][3] . '</div>';
                                       $num3++;
                                       //----------end----------//
                                   }else{
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                   }

                                 echo '</td>';
                                 //-----------------------------輸出大小-----------------------------
                                 echo '<td>';
                                   if($data[2][2][0][$num2]!=""){
                                         //以下的if分別是主隊的球投是零、是小於零、是大於零，的情況
                                         if($data[2][2][0][$num2][1]==0){
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][0][$num2][0] . '</div>';
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][2] . '</div>';

                                         }else if($data[2][2][0][$num2][1]<0){
                                           $data[2][2][0][$num2][1]=abs($data[2][2][0][$num2][1]);
                                           $point=$data[2][2][0][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if($point%5!=0){
                                              $OutPoint1=$data[2][2][0][$num2][1]-0.25;
                                              $OutPoint2=$data[2][2][0][$num2][1]+0.25;
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . '-' . $OutPoint2. "&nbsp&nbsp&nbsp" . $data[2][2][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][2] . '</div>';

                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][2] . '</div>';
                                           }
                                         }else{
                                           $point=$data[2][2][0][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if ($point%5!=0) {
                                               $OutPoint1=$data[2][2][0][$num2][1]-0.25;
                                               $OutPoint2=$data[2][2][0][$num2][1]+0.25;
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][0] . '</div>';
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . "-" . $OutPoint2 . "&nbsp&nbsp&nbsp" . $data[2][2][0][$num2][2] . '</div>';
                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][0][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][0][$num2][2] . '</div>';
                                           }
                                       }
                                       //----------MarketStatus---------//
                                      
                                       echo '<div id="status'. $num3 .'" style="display: none;" align="right">' . $data[2][2][0][$num2][3] . '</div>';
                                       $num3++;
                                       //----------end----------//
                                   }else{
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                   }
                                 echo '</td>';
                                 //-----------------------------輸出上半場大小-----------------------------
                                 echo '<td>';
                                   if($data[2][2][1][$num2]!=""){
                                         //以下的if分別是主隊的球投是零、是小於零、是大於零，的情況
                                         if($data[2][2][1][$num2][1]==0){
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][1][$num2][0] . '</div>';
                                           echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][2] . '</div>';

                                         }else if($data[2][2][1][$num2][1]<0){

                                           $data[2][2][1][$num2][1]=abs($data[2][2][1][$num2][1]);
                                           $point=$data[2][2][1][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if($point%5!=0){
                                              $OutPoint1=$data[2][2][1][$num2][1]-0.25;
                                              $OutPoint2=$data[2][2][1][$num2][1]+0.25;
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . '-' . $OutPoint2. "&nbsp&nbsp&nbsp" . $data[2][2][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][2] . '</div>';

                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][2] . '</div>';
                                           }
                                         }else{
                                           $point=$data[2][2][1][$num2][1]*10;
                                           //用除以0.5來判斷是否為雙球投
                                           if ($point%5!=0) {
                                               $OutPoint1=$data[2][2][1][$num2][1]-0.25;
                                               $OutPoint2=$data[2][2][1][$num2][1]+0.25;
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][0] . '</div>';
                                               echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $OutPoint1 . "-" . $OutPoint2 . "&nbsp&nbsp&nbsp" . $data[2][2][1][$num2][2] . '</div>';
                                           }else{
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][0] . '</div>';
                                              echo '<div align="right" onclick="changeOutput(' . $num3 . ')">' . $data[2][2][1][$num2][1] . "&nbsp&nbsp&nbsp" . $data[2][2][1][$num2][2] . '</div>';
                                           }
                                       }
                                       //----------MarketStatus---------//
                                       
                                       echo '<div id="status'. $num3 .'" style="display: none;" align="right">' . $data[2][2][1][$num2][3] . '</div>';
                                       $num3++;
                                       //----------end----------//
                                   }else{
                                       echo '<div align="right">' . ' ' . '</div>';
                                       echo '<div align="right">' . ' ' . '</div>';
                                   }
                                 echo '</td>';
                                 //-----------------------------market_status的狀態-----------------------------
                                 
                                 $num2++;
                                 echo '</tr>';

                               }

                             }
                        ?>


                      </tbody>
                  </table>
              </div>

      <script>
         function changeOutput(id) {
            $('#status'+id).show();
            //$('#inputBut'+id).hide();
            console.log("into change");
         }
      </script>
  </body>
</html>
