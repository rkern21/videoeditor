
2
3
4
5
6
7
8
9
10
11
12
13
14
15
16
17
18
19
20
21
22
23
24
25
26
27
28
29
30
31
32
33
34
35
36
37
38
39
40
41
42
43
44
45
46
47
48
49
50
51
window.addEvent('domready', function() {
	if (MooTools.version < "2") {
		var itemModules = $('paramsfusion_children_typemodules'), itemPositions = $('paramsfusion_children_typemodulepos'), itemMenu = $('paramsfusion_children_typemenuitems');
		var blockModules = $('paramsfusion_modules'), blockPositions = $('paramsfusion_module_positions');
	} else {
		var itemModules = document.id('paramsfusion_children_typemodules'), itemPositions = document.id('paramsfusion_children_typemodulepos'), itemMenu = document.id('paramsfusion_children_typemenuitems');
		var blockModules = document.id('paramsfusion_modules'), blockPositions = document.id('paramsfusion_module_positions');
	}
	if (blockModules) var blockModulesTr = blockModules.getParent().getParent();
	if (blockPositions) var blockPositionsTr = blockPositions.getParent().getParent();
	if (itemModules && blockModules) {
		itemModules.addEvent('click', function() {
			if (blockPositionsTr) blockPositionsTr.setStyle('display', 'none');
			if (blockModulesTr) blockModulesTr.setStyle('display', 'table-row');
			var tbody = blockModulesTr.getParent().getParent();
			var wrapper = tbody.getParent();
			if (wrapper.getStyle('height').toInt() > 0) {
				if (MooTools.version < "2")  wrapper.setStyle('height', tbody.getSize().size.y);
				else wrapper.setStyle('height', tbody.getSize().y);
			}
		});
	}
	if (itemPositions && blockPositions) {
		itemPositions.addEvent('click', function() {
			if (blockModulesTr) blockModulesTr.setStyle('display', 'none');
			if (blockPositionsTr) blockPositionsTr.setStyle('display', 'table-row');
			var tbody = blockPositionsTr.getParent().getParent();
			var wrapper = tbody.getParent();
			if (wrapper.getStyle('height').toInt() > 0) {
				if (MooTools.version < "2")  wrapper.setStyle('height', tbody.getSize().size.y);
				else wrapper.setStyle('height', tbody.getSize().y);
			}
		});
	}
	if (itemMenu) {
		itemMenu.addEvent('click', function() {
			if (blockModulesTr) blockModulesTr.setStyle('display', 'none');
			if (blockPositionsTr) blockPositionsTr.setStyle('display', 'none');
			var tbody = blockModulesTr.getParent().getParent();
			var wrapper = tbody.getParent();
			if (wrapper.getStyle('height').toInt() > 0) {
				if (MooTools.version < "2")  wrapper.setStyle('height', tbody.getSize().size.y);
				else wrapper.setStyle('height', tbody.getSize().y);
			}
		});
	}

	if (itemMenu.checked) itemMenu.fireEvent('click');
	if (itemModules.checked) itemModules.fireEvent('click');
	if (itemPositions.checked) itemPositions.fireEvent('click');
});