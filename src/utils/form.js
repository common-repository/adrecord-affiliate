// type DatabaseColumnObject = {
//   [id: string]: string
// }

export function convertFormData(target) {
  let formData = new FormData(target)
  let outputLog = {},
    iterator = formData.entries(),
    end = false
  while (end == false) {
    let item = iterator.next()
    if (item.value != undefined) {
      outputLog[item.value[0]] = item.value[1]
    } else if (item.done == true) {
      end = true
    }
  }
  return outputLog
}
// export function convertFormDataToArray(
//   target: HTMLFormElement,
// ): DatabaseColumnObject[] {
//   let formData = new FormData(target)
//   let outputLog = [],
//     iterator = formData.entries(),
//     end = false
//   while (end == false) {
//     let item = iterator.next()
//     if (item.value != undefined) {
//       outputLog.push({
//         [item.value[0]]: item.value[1],
//       })
//     } else if (item.done == true) {
//       end = true
//     }
//   }
//   return outputLog
// }
