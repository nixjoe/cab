<?php if ($info): ?>
<div class="grid-view" id="trade-info-grid" style="padding-top: 0">
    <table id="trade-table-info" class="shadowed padtable content-box widetable" style="width: 100%">
        <thead>
            <tr>
                <th>Order</th>
                <th>Open Time</th>
                <th>Type</th>
                <th>Volume</th>
                <th>Symbol</th>
                <th>Price</th>
                <th>S/L</th>
                <th>T/P</th>
                <th>Price</th>
                <th>Commission</th>
                <th>Swap</th>
                <th>Profit</th>
            </tr>
        </thead>
        <?php foreach($info['data'] as $row): ?>
            <tr class="trow">
                <td><?=$row['order']?></td>
                <td><?=$row['time']?></td>
                <td><?=$row['type']?></td>
                <td><nobr><?=$row['lots']?></nobr></td>
                <td><?=strtolower($row['symbol'])?></td>
                <td><nobr><?=$row['price']?><nobr></td>
                <td><nobr><?=$row['sl']?><nobr></td>
                <td><nobr><?=$row['tp']?><nobr></td>
                <td><nobr><?=$row['price2']?><nobr></td>
                <td><nobr><?=$row['commission']?><nobr></td>
                <td><nobr><?=$row['swap']?><nobr></td>
                <td><nobr><?=$row['profit']?><nobr></td>
            </tr>
        <?php endforeach; ?>

        <tr class="trow footer-row">
            <td colspan="11">
                <b>Balance: <nobr><?=@$info['balance']?></nobr>
                    Equity: <nobr><?=@$info['equity']?></nobr>
                    Margin: <nobr><?=@$info['margin']?></nobr>
                    Free margin: <nobr><?=@$info['free_margin']?></nobr>
                    Margin level: <nobr><?=@$info['margin_level']?></nobr></b></td>
            <td><b><?=@$info['profit']?></b></td>
        </tr>
        <?php foreach($info['data_ext'] as $row):?>
            <tr class="trow">
                <td><?=@$row['order']?></td>
                <td><?=@$row['time']?></td>
                <td><?=@$row['type']?></td>
                <td><nobr><?=@$row['lots']?></nobr></td>
                <td><?=strtolower(@$row['symbol'])?></td>
                <td><nobr><?=@$row['price']?><nobr></td>
                <td><nobr><?=@$row['sl']?><nobr></td>
                <td><nobr><?=@$row['tp']?><nobr></td>
                <td><nobr><?=@$row['price2']?><nobr></td>
                <td colspan="3">&nbsp;</td>
            </tr>
        <?php endforeach; ?>
    </table>

    <p>
        &nbsp;
    </p>
</div>
<?php endif; ?>