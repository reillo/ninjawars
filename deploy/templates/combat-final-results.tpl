    <table style="border: 0;">
      <tr>
        <th colspan="3">Results of the Attack</th>
      </tr>
      <tr>
        <td>Name</td>
        <td>Total Dmg</td>
        <td>HP</td>
      </tr>
  
      <tr>
        <td>{$attacker|escape}</td>
        <td style="text-align: center;">{$total_attacker_damage|escape}</td>
        <td>
{if $finalizedHealth lt 100} {* Makes your health red if you go below 100 hitpoints. *}
          <span style="color:red;font-weight:bold;">
{else} {* Normal text color for health. *}
          <span style="color:brown;font-weight:normal;">
{/if}
  
            {$finalizedHealth|escape}
          </span>
        </td>
      </tr>
      <tr>
        <td>{$target|escape}</td>
        <td style="text-align: center;">{$total_target_damage|escape}</td>
        <td>{math equation="x - y" x=$target_health y=$total_attacker_damage}</td>
      </tr>
    </table>
    <hr>
