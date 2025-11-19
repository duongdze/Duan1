document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('tour-table');
    if (!table) return;

    const tbody = table.querySelector('tbody');
    const originalOrder = Array.from(tbody.querySelectorAll('tr'));

    const rulesContainer = document.getElementById('sort-rules');
    const ruleTemplate = document.getElementById('sort-rule-template');
    const addRuleBtn = document.getElementById('add-sort-rule');
    const clearRuleBtn = document.getElementById('clear-sort');
    const applySortBtn = document.getElementById('apply-sort');

    const MAX_RULES = 3;
    const columnTypes = {
        name: 'text',
        type: 'text',
        supplier: 'text',
        created_at: 'number',
        base_price: 'number',
    };

    function addRule(defaults = { column: 'created_at', direction: 'desc' }) {
        if (!rulesContainer || !ruleTemplate) return;
        if (rulesContainer.children.length >= MAX_RULES) return;

        const clone = ruleTemplate.content.cloneNode(true);
        const ruleEl = clone.querySelector('.sort-rule');
        const columnSelect = ruleEl.querySelector('[data-field="column"]');
        const directionSelect = ruleEl.querySelector('[data-field="direction"]');

        if (defaults.column) columnSelect.value = defaults.column;
        if (defaults.direction) directionSelect.value = defaults.direction;

        rulesContainer.appendChild(ruleEl);
    }

    function getRules() {
        if (!rulesContainer) return [];
        return Array.from(rulesContainer.querySelectorAll('.sort-rule')).map((rule) => {
            const column = rule.querySelector('[data-field="column"]')?.value;
            const direction = rule.querySelector('[data-field="direction"]')?.value;
            return column ? { column, direction: direction || 'asc' } : null;
        }).filter(Boolean);
    }

    function updateIndicators(rules) {
        const indicators = table.querySelectorAll('.sort-indicator');
        indicators.forEach((indicator) => {
            indicator.classList.remove('active');
            indicator.textContent = '';
        });

        rules.forEach((rule, index) => {
            const indicator = table.querySelector(`.sort-indicator[data-col="${rule.column}"]`);
            if (!indicator) return;
            indicator.classList.add('active');
            indicator.innerHTML = `<i class="fas fa-sort-${rule.direction === 'asc' ? 'up' : 'down'} me-1"></i>${index + 1}`;
        });
    }

    function sortRows() {
        const rules = getRules();
        if (!rules.length) {
            updateIndicators([]);
            return;
        }

        const rows = Array.from(tbody.querySelectorAll('tr'));
        const sortedRows = rows.sort((rowA, rowB) => {
            for (const rule of rules) {
                const type = columnTypes[rule.column] || 'text';
                const valueA = rowA.getAttribute(`data-sort-${rule.column}`) ?? '';
                const valueB = rowB.getAttribute(`data-sort-${rule.column}`) ?? '';

                let comparison = 0;
                if (type === 'number') {
                    comparison = (parseFloat(valueA) || 0) - (parseFloat(valueB) || 0);
                } else {
                    comparison = valueA.localeCompare(valueB, 'vi', { sensitivity: 'base', numeric: true });
                }

                if (comparison !== 0) {
                    return rule.direction === 'asc' ? comparison : -comparison;
                }
            }
            return 0;
        });

        const fragment = document.createDocumentFragment();
        sortedRows.forEach((row) => fragment.appendChild(row));
        tbody.appendChild(fragment);
        updateIndicators(rules);
    }

    addRuleBtn?.addEventListener('click', function () {
        addRule();
    });

    clearRuleBtn?.addEventListener('click', function () {
        if (!rulesContainer) return;
        rulesContainer.innerHTML = '';
        const fragment = document.createDocumentFragment();
        originalOrder.forEach((row) => fragment.appendChild(row));
        tbody.appendChild(fragment);
        updateIndicators([]);
    });

    applySortBtn?.addEventListener('click', function () {
        sortRows();
    });

    rulesContainer?.addEventListener('click', function (event) {
        const removeBtn = event.target.closest('.remove-sort-rule');
        if (!removeBtn) return;
        removeBtn.closest('.sort-rule')?.remove();
    });

    // Initialize with a default rule
    addRule({ column: 'created_at', direction: 'desc' });
    updateIndicators([]);
});

