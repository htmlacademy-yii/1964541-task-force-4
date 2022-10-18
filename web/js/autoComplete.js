const autoCompleteJS = new autoComplete({
    placeHolder: "Введите адрес",
    data: {
        src: async (query) => {
            try {
                const source = await fetch(`autocomplete/${query}`);
                let data = await source.json();

                return data;
            } catch (error) {
                return error;
            }
        },
        cache: false,
    },
    resultItem: {
        highlight: true
    },
    events: {
        input: {
            selection: (event) => {
                const selection = event.detail.selection.value;
                autoCompleteJS.input.value = selection;
            }
        }
    }
});