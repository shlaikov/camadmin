export default `<?xml version="1.0" encoding="UTF-8"?>
<definitions xmlns="https://www.omg.org/spec/DMN/20191111/MODEL/" xmlns:dmndi="https://www.omg.org/spec/DMN/20191111/DMNDI/" xmlns:dc="http://www.omg.org/spec/DMN/20180521/DC/" xmlns:di="http://www.omg.org/spec/DMN/20180521/DI/" id="dish" name="Dish" namespace="http://camunda.org/schema/1.0/dmn">
  <inputData id="dayType_id" name="Type of day">
    <variable id="dayType_ii" name="Type of day" typeRef="string" />
  </inputData>
  <inputData id="temperature_id" name="Weather in Celsius">
    <variable id="temperature_ii" name="Weather in Celsius" typeRef="integer" />
  </inputData>
  <knowledgeSource id="host_ks" name="Host" />
  <knowledgeSource id="guest_ks" name="Guest Type">
    <authorityRequirement id="AuthorityRequirement_0hyfuzo">
      <requiredDecision href="#guestCount" />
    </authorityRequirement>
  </knowledgeSource>
  <businessKnowledgeModel id="elMenu" name="Menu" />
  <decision id="dish-decision" name="Dish Decision">
    <informationRequirement id="InformationRequirement_05tgz9d">
      <requiredDecision href="#guestCount" />
    </informationRequirement>
    <informationRequirement id="InformationRequirement_1r8doop">
      <requiredDecision href="#season" />
    </informationRequirement>
    <authorityRequirement id="AuthorityRequirement_1sk6rin">
      <requiredAuthority href="#host_ks" />
    </authorityRequirement>
    <decisionTable id="dishDecisionTable">
      <input id="seasonInput" label="Season">
        <inputExpression id="seasonInputExpression" typeRef="string">
          <text>season</text>
        </inputExpression>
      </input>
      <input id="guestCountInput" label="How many guests">
        <inputExpression id="guestCountInputExpression" typeRef="integer">
          <text>guestCount</text>
        </inputExpression>
      </input>
      <output id="output1" label="Dish" name="desiredDish" typeRef="string" />
      <rule id="row-495762709-1">
        <inputEntry id="UnaryTests_1nxcsjr">
          <text>"Winter"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_1r9yorj">
          <text>&lt;= 8</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1mtwzqz">
          <text>"Spareribs"</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-2">
        <inputEntry id="UnaryTests_1lxjbif">
          <text>"Winter"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_0nhiedb">
          <text>&gt; 8</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1h30r12">
          <text>"Pasta"</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-3">
        <inputEntry id="UnaryTests_0ifgmfm">
          <text>"Summer"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_12cib9m">
          <text>&gt; 10</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_0wgaegy">
          <text>"Light salad"</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-7">
        <inputEntry id="UnaryTests_0ozm9s7">
          <text>"Summer"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_0sesgov">
          <text>&lt;= 10</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1dvc5x3">
          <text>"Beans salad"</text>
        </outputEntry>
      </rule>
      <rule id="row-445981423-3">
        <inputEntry id="UnaryTests_1er0je1">
          <text>"Spring"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_1uzqner">
          <text>&lt; 10</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1pxy4g1">
          <text>"Stew"</text>
        </outputEntry>
      </rule>
      <rule id="row-445981423-4">
        <inputEntry id="UnaryTests_06or48g">
          <text>"Spring"</text>
        </inputEntry>
        <inputEntry id="UnaryTests_0wa71sy">
          <text>&gt;= 10</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_09ggol9">
          <text>"Steak"</text>
        </outputEntry>
      </rule>
    </decisionTable>
  </decision>
  <decision id="season" name="Season decision">
    <informationRequirement id="InformationRequirement_1vzoh7s">
      <requiredInput href="#temperature_id" />
    </informationRequirement>
    <decisionTable id="seasonDecisionTable">
      <input id="temperatureInput" label="Weather in Celsius">
        <inputExpression id="temperatureInputExpression" typeRef="integer">
          <text>temperature</text>
        </inputExpression>
      </input>
      <output id="seasonOutput" label="season" name="season" typeRef="string" />
      <rule id="row-495762709-5">
        <inputEntry id="UnaryTests_1fd0eqo">
          <text>&gt;30</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_0l98klb">
          <text>"Summer"</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-6">
        <inputEntry id="UnaryTests_1nz6at2">
          <text>&lt;10</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_08moy1k">
          <text>"Winter"</text>
        </outputEntry>
      </rule>
      <rule id="row-445981423-2">
        <inputEntry id="UnaryTests_1a0imxy">
          <text>[10..30]</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1poftw4">
          <text>"Spring"</text>
        </outputEntry>
      </rule>
    </decisionTable>
  </decision>
  <decision id="guestCount" name="Guest Count">
    <informationRequirement id="InformationRequirement_038230q">
      <requiredInput href="#dayType_id" />
    </informationRequirement>
    <knowledgeRequirement id="KnowledgeRequirement_0cql475">
      <requiredKnowledge href="#elMenu" />
    </knowledgeRequirement>
    <decisionTable id="guestCountDecisionTable">
      <input id="typeOfDayInput" label="Type of day">
        <inputExpression id="typeOfDayInputExpression" typeRef="string">
          <text>dayType</text>
        </inputExpression>
      </input>
      <output id="guestCountOutput" label="Guest count" name="guestCount" typeRef="integer" />
      <rule id="row-495762709-8">
        <inputEntry id="UnaryTests_0l72u8n">
          <text>"Weekday"</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_0wuwqaz">
          <text>4</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-9">
        <inputEntry id="UnaryTests_03a73o9">
          <text>"Holiday"</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1whn119">
          <text>10</text>
        </outputEntry>
      </rule>
      <rule id="row-495762709-10">
        <inputEntry id="UnaryTests_12tygwt">
          <text>"Weekend"</text>
        </inputEntry>
        <outputEntry id="LiteralExpression_1b5k9t8">
          <text>15</text>
        </outputEntry>
      </rule>
    </decisionTable>
  </decision>
  <textAnnotation id="TextAnnotation_1">
    <text>Week day or week end</text>
  </textAnnotation>
  <association id="Association_18hoj4i">
    <sourceRef href="#dayType_id" />
    <targetRef href="#TextAnnotation_1" />
  </association>
  <dmndi:DMNDI>
    <dmndi:DMNDiagram id="DMNDiagram_1ejukud">
      <dmndi:DMNShape id="DMNShape_1pny77l" dmnElementRef="dayType_id">
        <dc:Bounds height="45" width="125" x="303" y="363" />
      </dmndi:DMNShape>
      <dmndi:DMNShape id="DMNShape_1b88mi9" dmnElementRef="temperature_id">
        <dc:Bounds height="45" width="125" x="105" y="316" />
      </dmndi:DMNShape>
      <dmndi:DMNShape id="DMNShape_0w9hu9e" dmnElementRef="host_ks">
        <dc:Bounds height="63" width="100" x="595" y="56" />
      </dmndi:DMNShape>
      <dmndi:DMNShape id="DMNShape_0159egh" dmnElementRef="guest_ks">
        <dc:Bounds height="63" width="100" x="587" y="194" />
      </dmndi:DMNShape>
      <dmndi:DMNEdge id="DMNEdge_1gafs9m" dmnElementRef="AuthorityRequirement_0hyfuzo">
        <di:waypoint x="510" y="226" />
        <di:waypoint x="587" y="226" />
      </dmndi:DMNEdge>
      <dmndi:DMNShape id="DMNShape_0j9biml" dmnElementRef="elMenu">
        <dc:Bounds height="46" width="135" x="542" y="364" />
      </dmndi:DMNShape>
      <dmndi:DMNShape id="DMNShape_1f9xq97" dmnElementRef="dish-decision">
        <dc:Bounds height="80" width="180" x="250" y="56" />
      </dmndi:DMNShape>
      <dmndi:DMNEdge id="DMNEdge_0wk9owu" dmnElementRef="InformationRequirement_05tgz9d">
        <di:waypoint x="395" y="186" />
        <di:waypoint x="365" y="136" />
      </dmndi:DMNEdge>
      <dmndi:DMNEdge id="DMNEdge_0glygnk" dmnElementRef="InformationRequirement_1r8doop">
        <di:waypoint x="243" y="186" />
        <di:waypoint x="297" y="136" />
      </dmndi:DMNEdge>
      <dmndi:DMNEdge id="DMNEdge_1jf14ck" dmnElementRef="AuthorityRequirement_1sk6rin">
        <di:waypoint x="595" y="89" />
        <di:waypoint x="430" y="94" />
      </dmndi:DMNEdge>
      <dmndi:DMNShape id="DMNShape_1dlhv62" dmnElementRef="season">
        <dc:Bounds height="80" width="180" x="110" y="186" />
      </dmndi:DMNShape>
      <dmndi:DMNEdge id="DMNEdge_01c572k" dmnElementRef="InformationRequirement_1vzoh7s">
        <di:waypoint x="180" y="316" />
        <di:waypoint x="191" y="266" />
      </dmndi:DMNEdge>
      <dmndi:DMNShape id="DMNShape_0tndkvg" dmnElementRef="guestCount">
        <dc:Bounds height="80" width="180" x="330" y="186" />
      </dmndi:DMNShape>
      <dmndi:DMNEdge id="DMNEdge_0wrc9rz" dmnElementRef="KnowledgeRequirement_0cql475">
        <di:waypoint x="591" y="364" />
        <di:waypoint x="510" y="265" />
      </dmndi:DMNEdge>
      <dmndi:DMNEdge id="DMNEdge_0m045nr" dmnElementRef="InformationRequirement_038230q">
        <di:waypoint x="369" y="363" />
        <di:waypoint x="405" y="266" />
      </dmndi:DMNEdge>
      <dmndi:DMNShape id="DMNShape_1izzhzd" dmnElementRef="TextAnnotation_1">
        <dc:Bounds height="45" width="125" x="273" y="466" />
      </dmndi:DMNShape>
      <dmndi:DMNEdge id="DMNEdge_1mkr3rl" dmnElementRef="Association_18hoj4i">
        <di:waypoint x="366" y="408" />
        <di:waypoint x="336" y="466" />
      </dmndi:DMNEdge>
    </dmndi:DMNDiagram>
  </dmndi:DMNDI>
</definitions>`
